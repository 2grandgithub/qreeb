<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Category;
use App\Models\Company;
use App\Models\ItemRequestState;
use App\Models\Order;
use App\Models\OrderItemUser;
use App\Models\OrderRate;
use App\Models\OrderTechRequest;
use App\Models\OrderUserDetail;
use App\Models\Collaboration;
use App\Models\ProviderCategoryFee;
use App\Models\PushNotify;
use App\Models\Technician;
use App\Models\TechNot;
use App\Models\TechToken;
use App\Models\User;
use App\Models\UserNot;
use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function get_techs(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'jwt' => 'required|exists:users,jwt,id,' . $request->user_id,
                'company_id' => 'required|exists:companies,id|exists:users,company_id,id,' . $request->user_id,
                'cat_id' => 'required|exists:categories,id'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $user = User::where('id', $request->user_id)->select('company_id', 'lat', 'lng')->first();
        $providers = Collaboration::where('company_id', $user->company_id)->pluck('provider_id');
        $techs = Order::filterbylatlng($user->lat, $user->lng, 50, 'technicians', $request->cat_id, $providers);

        $user['lat'] = $user->lat;
        $user['lng'] = $user->lng;

        unset($user->company_id);

        return response()->json(['techs' => $techs, 'user' => $user]);
    }


    public function search_techs(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'jwt' => 'required|exists:users,jwt,id,' . $request->user_id,
                'cat_id' => 'required|exists:categories,id,type,2'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $lang = $request->header('lang');

        $user = User::where('id', $request->user_id)->select('company_id', 'lat', 'lng')->first();
        $providers = Collaboration::where('company_id', $user->company_id)->pluck('provider_id');
        $techs = Technician::whereIn('provider_id', $providers)->where('busy', 0)->where('cat_ids','like','%'.$request->cat_id.'%')->select('id', 'busy', $lang . '_name as name', 'image', 'lat', 'lng')->get();

        foreach ($techs as $tech)
        {
            $tech['image'] = 'http://' . $_SERVER['SERVER_NAME'] . '/public/providers/technicians/' . $tech->image;
            $tech['rate'] = $tech->get_all_rate($tech->id);
        }

        return response()->json($techs);
    }


    public function view_tech(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'jwt' => 'required|exists:users,jwt,id,' . $request->user_id,
                'cat_id' => 'required|exists:categories,id,type,2',
                'tech_id' => 'required|exists:technicians,id',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $lang = $request->header('lang');

        $user = User::where('id', $request->user_id)->select('lat', 'lng')->first();

        $tech = Technician::where('id', $request->tech_id)->select('id','provider_id',$lang . '_name as name', 'cat_ids', 'image', 'lat', 'lng')->first();
        $tech['categories'] = $tech->get_categories($lang, $tech->cat_ids);
        $tech['rate'] = $tech->get_all_rate($tech->id);
        $tech['image'] = 'http://' . $_SERVER['SERVER_NAME'] . '/public/providers/technicians/' . $tech->image;
        $tech['distance'] = $tech->get_distance($tech, $user);
        $tech['fee'] = $tech->get_service_fee($tech->provider_id,$request->cat_id);

        return response()->json($tech);
    }

    public function order(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'jwt' => 'required|exists:users,jwt,id,' . $request->user_id,
                'type' => 'required|in:urgent,scheduled',
                'smo' => 'sometimes',
                'tech_id' => 'sometimes|exists:technicians,id',
                'scheduled_at' => 'sometimes|date',
                'category_id' => 'required|exists:categories,id,type,2',
                'images' => 'sometimes',
                'images.*' => 'image'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $company_id = User::where('id', $request->user_id)->first()->company_id;

        $providers = Collaboration::where('company_id', $company_id)->pluck('provider_id');
        if($request->type == 'urgent')
        {
            $tech_check = Technician::where('id', $request->tech_id)->whereIn('provider_id', $providers)->where('busy', 0)->first();
            if(! $tech_check) return response()->json(msg($request, 'invalid_tech', 'invalid_tech'));
        }


        $smo_check = Order::where('smo', $request->smo)->first();
        if($smo_check) return response()->json(['status' => 'error', 'msg' => 'smo already exists']);

        $order = new Order();
            $order->smo = $request->smo;
            $order->user_id = $request->user_id;
            $order->company_id = $company_id;
            $order->type = $request->type;
            $order->code = rand(1000, 9999);
            $order->cat_id = $request->category_id;
            if ($request->type == 'urgent')
            {
                if ($request->tech_id)
                {
                    $order->tech_id = $request->tech_id;
                    $order->provider_id = Technician::where('id', $request->tech_id)->first()->provider_id;
                    $order->order_total = ProviderCategoryFee::where('provider_id', $order->provider_id)->where('cat_id', $request->category_id)->select('fee')->first()->fee;
                    Technician::where('id', $order->tech_id)->update(['busy' => 1]);
                } else
                {
                    response()->json(['status' => 'failed', 'msg' => 'invalid tech_id']);
                }

            } else
            {
                if ($request->scheduled_at) $order->scheduled_at = $request->scheduled_at;
                else return response()->json(['status' => 'failed', 'msg' => 'invalid scheduled_at']);
            }
        $order->save();

        if ($order->tech_id != NULL)
        {
            $ar_text = 'لديك طلب خدمة جديد,الرجاء الإستجابة';
            $en_text = 'You have a new order request,please respond';

            TechNot::create
            (
                [
                    'type' => 'order',
                    'tech_id' => $order->tech_id,
                    'order_id' => $order->id,
                    'ar_text' => $ar_text,
                    'en_text' => $en_text
                ]
            );

            $token = TechToken::where('tech_id', $order->tech_id)->pluck('token');

            PushNotify::tech_send($token, $ar_text, $en_text, 'order', $order->id);
        }

        $order_details = new OrderUserDetail();
            $order_details->order_id = $order->id;
            $order_details->place = $request->place;
            $order_details->part = $request->part;
            $order_details->desc = $request->desc;
            if ($request->images)
            {
                $names = [];
                foreach ($request->images as $image)
                {
                    $name = unique_file($image->getClientOriginalName());
                    $image->move(base_path() . '/public/orders/', $name);

                    $names[] = $name;
                }
                $order_details->images = serialize($names);
            }
        $order_details->save();

        return response()->json(msg($request, success(), 'please_wait'));
    }


    public function orders(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'jwt' => 'required|exists:users,jwt,id,' . $request->user_id,

            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $lang = $request->header('lang');

        $orders = Order::where('user_id', $request->user_id)->select('id', 'type', 'tech_id', 'completed','canceled','scheduled_at', 'created_at')->latest()->get();
        foreach ($orders as $order) {
            $tech = $order->get_tech($lang, $order->tech_id);

            $order['type_text'] = $order->get_type($lang, $order->type);
            $order['tech_name'] = isset($order->tech_id) ? $tech->name : '';
            $order['tech_phone'] = isset($order->tech_id) ? $tech->phone : '';
            $order['tech_id'] = isset($order->tech_id) ? $order->tech_id : 0;

            if ($order->type == 'urgent') {
                $date = $order->created_at->toDateTimeString();
            } else {
                $date = Carbon::parse($order->scheduled_at)->toDateTimeString();
            }

            $order['date'] = $date;

            unset($order->scheduled_at, $order->created_at);
        }

        return response()->json($orders);
    }


    public function details(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'jwt' => 'required|exists:users,jwt,id,' . $request->user_id,
                'order_id' => 'required|exists:orders,id,user_id,' . $request->user_id,

            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $lang = $request->header('lang');

        $order = Order::where('id', $request->order_id)->first();
        $order['smo'] = isset($order->smo) ? (string)$order->smo : '';
        $order['details'] = $order->get_details($order->id);
        $order['items_requested'] = $order->get_items_awaiting($lang, $order->id);
        $order['items_submitted'] = $order->get_items($lang, $order->id);
        $order['location'] = $order->get_user_location($order->user_id);
        $order['category'] = $order->get_category($lang, $order->cat_id);

        if ($order->type == 'urgent') $date = $order->created_at->toDateTimeString();
        elseif ($order->type == 're_scheduled' && $order->scheduled_at == NULL) $date = '';
        else $date = $order->scheduled_at;

        $order['date'] = $date;
        $order['steps'] = $order->get_steps($lang,$order->id);
        $order['canceled_by'] = isset($order->canceled_by) ? $order->canceled_by : '';

        if ($order->type == 'scheduled')
        {
            $order['provider_id'] = isset($order->provider_id) ? $order->provider_id : 0;
            $order['cat_id'] = isset($order->cat_id) ? $order->cat_id : 0;
            $order['tech_name'] = isset($order->tech_id) ? $order->get_tech($lang, $order->tech_id)->name : '';
            $order['tech_image'] = isset($order->tech_id) ? 'http://' . $_SERVER['SERVER_NAME'] . '/public/providers/technicians/' . $order->get_tech($lang, $order->tech_id)->image : '';
            $order['tech_id'] = isset($order->tech_id) ? $order->tech_id : 0;
        }
        elseif ($order->type == 'urgent')
        {
            $order['tech_name'] = $order->get_tech($lang, $order->tech_id)->name;
            $order['tech_image'] = 'http://' . $_SERVER['SERVER_NAME'] . '/public/providers/technicians/' . $order->get_tech($lang, $order->tech_id)->image;
        }
        elseif ($order->type == 're_scheduled')
        {
            if (isset($order->tech_id))
            {
                $order['tech_name'] = $order->get_tech($lang, $order->tech_id)->name;
                $order['tech_image'] = 'http://' . $_SERVER['SERVER_NAME'] . '/public/providers/technicians/' . $order->get_tech($lang, $order->tech_id)->image;
            }
            else
            {
                $order['tech_id'] = 0;
                $order['tech_name'] = '';
                $order['tech_image'] = '';
            }
        }

        unset($order->scheduled_at, $order->created_at, $order->updated_at);

        return response()->json($order);
    }


    public function item_change_status(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'jwt' => 'required|exists:users,jwt,id,'.$request->user_id,
                'order_id' => 'required|exists:orders,id,user_id,'.$request->user_id,
                'request_id' => 'required|exists:order_item_users,id,order_id,'.$request->order_id,
                'status' => 'required|in:confirmed,declined'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        OrderItemUser::where('id', $request->request_id)->update
        (
            [
                'status' => $request->status
            ]
        );

        return response()->json(msg($request, success(), 'success'));
    }


    public function items_submit(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'jwt' => 'required|exists:users,jwt,id,' . $request->user_id,
                'order_id' => 'required|exists:orders,id,user_id,' . $request->user_id
            ]
        );

        if ($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $requested_items = OrderItemUser::where('order_id', $request->order_id)->where('status','!=','declined')->get();

        $check = $requested_items->pluck('status');

        if (in_array('awaiting', $check->toArray()))
        {
            return response()->json(msg($request, failed(), 'items_approval_missing'));
        }



        $company_id = User::where('id', $request->user_id)->select('company_id')->first()->company_id;
        $limit = Company::where('id', $company_id)->select('item_limit')->first()->item_limit;

        foreach ($requested_items as $item)
        {
            $order = Order::where('id', $request->order_id)->select('id','tech_id','item_total','order_total')->first();

            $this_item = DB::table($item->provider_id . '_warehouse_parts')->where('id', $item->item_id)->first();

            $item_request = new OrderTechRequest();
                $item_request->order_id = $request->order_id;
                $item_request->provider_id = $item->provider_id;
                $item_request->item_id = $item->item_id;
            $item_request->save();

            if ($this_item->count > 0)
            {

                if ($order->item_total + $this_item->price < $limit)
                {

                        $item_request->status = 'confirmed';
                        $item_request->save();

                        DB::table($item->provider_id . '_warehouse_parts')->where('id', $item->item_id)->update(['count' => $this_item->count - 1]);

                        $order->item_total = $this_item->price + $order->item_total;
                        $order->order_total = $this_item->price + $order->order_total;
                        $order->save();

                }
                else
                {
                    ItemRequestState::create
                    (
                        [
                            'request_id' => $item_request->id,
                            'company_id' => $company_id,
                            'provider_id' => $item->provider_id,
                        ]
                    );
                }
            }
            else
            {
                DB::table($item->provider_id . '_warehouse_parts')->where('id', $item->item_id)->update(['requested_count' => $this_item->requested_count + 1]);
            }
        }

        OrderItemUser::where('order_id', $request->order_id)->delete();

        Technician::where('id', $order->tech_id)->update(['busy' => 0]);
        Order::where('id', $request->order_id)->update(['type' => 're_scheduled','tech_id' => NULL]);

        TechNot::where('order_id', $request->order_id)->where('tech_id',$request->tech_id)->delete();

        $submitted = OrderTechRequest::where('order_id', $request->order_id)->get();

        if(in_array('awaiting', $submitted->pluck('status')->toArray()) == false)
        {
            $ar_text = 'تم الموافقة علي القطع المطلوبة للطلب خاصتك,الرجاء تحديد موعد لإعادة الزيارة';
            $en_text = 'The request for your order items is approved,please select the reschedule time';

            UserNot::create
            (
                [
                    'type' => 'time',
                    'user_id' => $request->user_id,
                    'order_id' => $request->order_id,
                    'ar_text' => $ar_text,
                    'en_text' => $en_text
                ]
            );

            $token = UserToken::where('user_id', $request->user_id)->pluck('token');
            PushNotify::user_send($token,$ar_text,$en_text,'time',$request->order_id);
        }
        else
        {
            $ar_text = 'سعر القطع أعلي من الحد الأقصي للموافقة التلقائية,في إنتظار الموافقة علي القطع خاصتك,الرجاءالإنتظار';
            $en_text = 'Parts prices for your order exceed the items price limit,parts are under approval,please wait';

            UserNot::create
            (
                [
                    'type' => 'order',
                    'user_id' => $request->user_id,
                    'order_id' => $request->order_id,
                    'ar_text' => $ar_text,
                    'en_text' => $en_text
                ]
            );

            $token = UserToken::where('user_id', $request->user_id)->pluck('token');
            PushNotify::user_send($token,$ar_text,$en_text,'order',$request->order_id);
        }

        return response()->json(msg($request,success(),'please_wait'));
    }


    public function re_schedule(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'jwt' => 'required|exists:users,jwt,id,'.$request->user_id,
                'order_id' => 'required|exists:orders,id,user_id,'.$request->user_id,
                'timestamp' => 'required',
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        Order::where('id', $request->order_id)->update
        (
            [
                'scheduled_at' => $request->timestamp,
            ]
        );

        UserNot::where('order_id', $request->order_id)->where('type', 'time')->delete();

        return response()->json(msg($request,success(),'success'));
    }


    public function view_tech_to_rate(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'jwt' => 'required|exists:users,jwt,id,'.$request->user_id,
                'order_id' => 'required|exists:orders,id,user_id,'.$request->user_id,
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $lang = $request->header('lang');

        $order = Order::where('id', $request->order_id)->select('tech_id','cat_id')->first();
        $tech = $order->get_tech_lang($lang,$order->tech_id,$order->cat_id);

        return response()->json($tech);
    }


    public function rate(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
            'user_id' => 'required|exists:users,id',
            'jwt' => 'required|exists:users,jwt,id,'.$request->user_id,
            'order_id' => 'required|exists:orders,id,user_id,'.$request->user_id,
            'appearance' => 'required',
            'cleanliness' => 'required',
            'performance' => 'required',
            'commitment' => 'required',

            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        OrderRate::create
        (
            [
                'order_id' => $request->order_id,
                'appearance' => $request->appearance,
                'cleanliness' => $request->cleanliness,
                'performance' => $request->performance,
                'commitment' => $request->commitment
            ]
        );

        UserNot::where('user_id', $request->user_id)->where('order_id', $request->order_id)->where('type', 'rate')->delete();

        return response()->json(msg($request,success(),'success'));
    }


    public function cancel(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'jwt' => 'required|exists:users,jwt,id,'.$request->user_id,
                'order_id' => 'required|exists:orders,id,user_id,'.$request->user_id,
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }


        $order = Order::where('id', $request->order_id)->select('id','provider_id','tech_id','cat_id','canceled','canceled_by')->first();
            $order->completed = 1;
            $order->canceled = 1;
            $order->canceled_by = 'user';
            $order->order_total = ProviderCategoryFee::where('provider_id', $order->provider_id)->where('cat_id', $order->cat_id)->select('fee')->first()->fee;
        $order->save();


        $ar_text = 'عذراً,لقد تم إلغاء الطلب من قبل المستخدم';
        $en_text = 'Sorry,the current order has been canceled by the user';

        TechNot::create
        (
            [
                'type' => 'order',
                'tech_id' => $order->tech_id,
                'order_id' => $request->order_id,
                'ar_text' => $ar_text,
                'en_text' => $en_text
            ]
        );


        $token = TechToken::where('tech_id', $order->tech_id)->pluck('token');
        PushNotify::tech_send($token,$ar_text,$en_text,'push',$request->order_id);

        return response()->json(msg($request,success(),'success'));
    }
}
