<?php

namespace App\Http\Controllers\Api\Tech;

use App\Models\Category;
use App\Models\Company;
use App\Models\ItemRequestState;
use App\Models\Order;
use App\Models\OrderItemUser;
use App\Models\OrderTechDetail;
use App\Models\OrderTechRequest;
use App\Models\ProviderCategoryFee;
use App\Models\PushNotify;
use App\Models\Technician;
use App\Models\TechNot;
use App\Models\User;
use App\Models\UserNot;
use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function orders(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,'.$request->tech_id,

            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $lang = $request->header('lang');

        $orders = Order::where('tech_id', $request->tech_id)->where('completed', 1)->select('id','type','user_id','completed','scheduled_at','created_at')->latest()->get();

        foreach($orders as $order)
        {
            $user = $order->get_user($lang,$order->user_id);

            $order['type_text'] = $order->get_type($lang,$order->type);
            $order['user_name'] = $user->name;
            $order['user_phone'] = $user->phone;

            if($order->type == 'urgent')
            {
                $date = $order->created_at->toDateTimeString();
            }
            elseif($order->type == 'urgent')
            {
                $date = Carbon::parse($order->created_at)->toDateTimeString();
            }
            else
            {
                $date = Carbon::parse($order->scheduled_at)->toDateTimeString();
            }

            $order['date'] = $date;

            unset($order->scheduled_at,$order->created_at);
        }

        $busy = Technician::where('id',$request->tech_id)->select('busy')->first()->busy;

        return response()->json(['orders' => $orders, 'busy' => $busy]);
    }


    public function details(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,'.$request->tech_id,
                'order_id' => 'required|exists:orders,id,tech_id,'.$request->tech_id,

            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }


        $lang = $request->header('lang');

        $order = Order::where('id', $request->order_id)->first();
        $order['smo'] = isset($order->smo) ? (string)$order->smo : '';
        $order['details'] = $order->get_details($order->id);
        $order['items_requested'] = $order->get_items_awaiting($lang,$order->id);
        $order['items_submitted'] = $order->get_items($lang,$order->id);
        $order['location'] = $order->get_user_location($order->user_id);
        $order['category'] = $order->get_category($lang,$order->cat_id);

        if($order->type == 'urgent') $date = $order->created_at->toDateTimeString();
        elseif($order->type == 're_scheduled' && $order->scheduled_at == NULL) $date = '';
        else $date = $order->scheduled_at;

        $order['canceled_by'] = isset($order->canceled_by) ? $order->canceled_by : '';
        $order['date'] = $date;
        $order['steps'] = $order->get_steps($lang,$order->id);

        unset($order->scheduled_at,$order->created_at,$order->updated_at);
        return response()->json($order);
    }


    public function warehouse_search(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,'.$request->tech_id,
                'search' => 'required'
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $lang = $request->header('lang');
        $provider_id = Technician::where('id', $request->tech_id)->where('jwt', $request->jwt)->select('provider_id')->first()->provider_id;

        $items = DB::table($provider_id.'_warehouse_parts')->where('active', 1)->where('count','>',0 )->where(function($q) use($request)
            {
                $q->where('ar_name','like','%'.$request->search.'%');
                $q->orWhere('en_name','like','%'.$request->search.'%');
                $q->orWhere('code','like','%'.$request->search.'%');

            }
        )->select('id',$lang.'_name as name',$lang.'_desc as desc','image','code','count','price')->paginate(30);

        foreach($items as $item)
        {
            $item->image = 'http://'.$_SERVER['SERVER_NAME'].'/public/warehouses/'.$item->image;
        }

        return response()->json($items);

    }


    public function warehouse_cats(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,'.$request->tech_id,
                'parent_id' => 'required'
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $lang = $request->header('lang');

        if($request->parent_id == 0)
        {
            $categories = Category::where('parent_id', NULL)->select('id',$lang.'_name as name')->get();
        }
        else
        {
            $categories = Category::where('parent_id', $request->parent_id)->select('id',$lang.'_name as name')->get();
        }

        return response()->json($categories);
    }


    public function warehouse_items(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,'.$request->tech_id,
                'cat_id' => 'required'
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $lang = $request->header('lang');
        $provider_id = Technician::where('id', $request->tech_id)->where('jwt', $request->jwt)->select('provider_id')->first()->provider_id;

        $items = DB::table($provider_id.'_warehouse_parts')->where('cat_id', $request->cat_id)->where('active', 1)->select('id',$lang.'_name as name',$lang.'_desc as desc','image','code','count','price')->paginate(30);

        foreach($items as $item)
        {
            $item->image = 'http://'.$_SERVER['SERVER_NAME'].'/public/warehouses/'.$item->image;
        }

        return response()->json($items);
    }


    public function warehouse_show_item(Request $request)
    {
        $provider_id = Technician::where('id', $request->tech_id)->where('jwt', $request->jwt)->select('provider_id')->first()->provider_id;
        $table = $provider_id . '_warehouse_parts';

        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,' . $request->tech_id,
                'item_id' => 'required|exists:' . $table . ',id,active,1'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $lang = $request->header('lang');
        $item = DB::table($provider_id.'_warehouse_parts')->where('id', $request->item_id)->select('id',$lang.'_name as name',$lang.'_desc as desc','image','code','count','price')->first();
        $item->image = 'http://'.$_SERVER['SERVER_NAME'].'/public/warehouses/'.$item->image;

        return response()->json($item);
    }


    public function warehouse_add_item(Request $request)
    {
        $provider_id = Technician::where('id', $request->tech_id)->where('jwt', $request->jwt)->select('provider_id')->first()->provider_id;
        $table = $provider_id.'_warehouse_parts';

        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,'.$request->tech_id,
                'order_id' => 'required|exists:orders,id,completed,0|exists:orders,id,tech_id,'.$request->tech_id,
                'item_id' => 'required|exists:'.$table.',id'
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $user_id = Order::where('id', $request->order_id)->select('user_id')->first()->user_id;

        OrderItemUser::create
        (
            [
                'order_id' => $request->order_id,
                'user_id' => $user_id,
                'item_id' => $request->item_id,
                'provider_id' => $provider_id
            ]
        );

        $ar_text = 'تم إضافة قطعة جديدة للطلب خاصتك من قبل الفني,الرجاء التحقق';
        $en_text = 'There is a new part added by the technician for your order,please check !';

        UserNot::create
        (
            [
                'type' => 'order',
                'user_id' => $user_id,
                'order_id' => $request->order_id,
                'ar_text' => $ar_text,
                'en_text' => $en_text
            ]
        );

        $token = UserToken::where('user_id', $user_id)->pluck('token');
        PushNotify::user_send($token,$ar_text,$en_text,'order',$request->order_id);

        return response()->json(msg($request,success(),'please_wait_user_approval'));
    }


    public function warehouse_request_item(Request $request)
    {
        $provider_id = Technician::where('id', $request->tech_id)->where('jwt', $request->jwt)->select('provider_id')->first()->provider_id;
        $table = $provider_id . '_warehouse_requests';

        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,' . $request->tech_id,
                'order_id' => 'required|exists:orders,id,completed,0|exists:orders,id,tech_id,'.$request->tech_id,                'title' => 'required',
                'desc' => 'required',
                'title' => 'required'
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        DB::table($table)->insert(
            [
                'order_id' => $request->order_id,
                'tech_id' => $request->tech_id,
                'title' => $request->title,
                'details' => $request->desc,
            ]
        );

        Technician::where('id', $request->tech_id)->update(['busy' => 0]);

        $order = Order::where('id', $request->order_id)->select('id','user_id','tech_id','type')->first();
            $order->tech_id = NULL;
            $order->type = 're_scheduled';
        $order->save();

        $ar_text = 'في إنتظار الموافقة علي القطعة المطلوبة للطلب خاصتك,الرجاءالإنتظار';
        $en_text = 'The request for your order item is under approval,please wait';

        UserNot::create
        (
            [
                'type' => 'order',
                'user_id' => $order->user_id,
                'order_id' => $request->order_id,
                'ar_text' => $ar_text,
                'en_text' => $en_text
            ]
        );

        $token = UserToken::where('user_id', $order->user_id)->pluck('token');
        PushNotify::user_send($token,$ar_text,$en_text,'order',$request->order_id);

        return response()->json(msg($request,success(),'success'));
    }


    public function get_third_levels(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,'.$request->tech_id,
                'order_id' => 'required|exists:orders,id,completed,0|exists:orders,id,tech_id,'.$request->tech_id,
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $lang = $request->header('lang');

        $order = Order::where('id', $request->order_id)->select('cat_id')->first();
        $levels = Category::where('parent_id', $order->cat_id)->select('id',$lang.'_name as title')->get();

        return response()->json($levels);
    }


    public function change_status(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,'.$request->tech_id,
                'order_id' => 'required|exists:orders,id,completed,0|exists:orders,id,tech_id,'.$request->tech_id,
                'status' => 'required|in:completed,re_scheduled',
                'code' => 'required',
                'type_id' => 'required|exists:categories,id',
                'desc' => 'required',
                'before_images' => 'required|array',
                'after_images' => 'required|array',
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $lang = $request->header('lang');

        $order = Order::where('id', $request->order_id)->where('code', $request->code)->first();

        if(!$order)
        {
            return response()->json(msg($request,failed(),'invalid_code'));
        }
        else
        {
            $details = new OrderTechDetail();
                $details->order_id = $request->order_id;
                $details->type_id = $request->type_id;
                $details->desc = $request->desc;

                $before = [];
                foreach($request->before_images as $image)
                {
                    $name = unique_file($image->getClientOriginalName());
                    $image->move(base_path().'/public/orders/',$name);
                    array_push($before,$name);
                }

                $after = [];
                foreach($request->after_images as $image)
                {
                    $name = unique_file($image->getClientOriginalName());
                    $image->move(base_path().'/public/orders/',$name);
                    array_push($after,$name);
                }
                $details->before_images = serialize($before);
                $details->after_images = serialize($after);
            $details->save();


            $order = Order::where('id', $request->order_id)->select('id','cat_id','completed','user_id','tech_id')->first();
                $order->completed = 1;
            $order->save();

            Technician::where('id', $request->tech_id)->update(['busy' => 0]);

            $token = UserToken::where('user_id', $order->user_id)->pluck('token');

            $ar_text = 'تم إنهاء الطلب بنجاح,الرجاء التقييم';
            $en_text = 'Order has been completed successfully,please rate';

            UserNot::create
            (
                [
                    'type' => 'rate',
                    'user_id' => $order->user_id,
                    'order_id' => $request->order_id,
                    'ar_text' => $ar_text,
                    'en_text' => $en_text
                ]
            );

            $tech = $order->get_tech_all($order->tech_id,$order->cat_id);

            PushNotify::user_send($token,$ar_text,$en_text,'rate',$order->id,$tech);

            return response()->json(msg($request,success(),'success'));
        }
    }


    public function cancel(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,' . $request->tech_id,
                'order_id' => 'required|exists:orders,id,tech_id,' . $request->tech_id,
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }


        $order = Order::where('id', $request->order_id)->select('id','provider_id','user_id','cat_id','canceled','canceled_by')->first();
            $order->completed = 1;
            $order->canceled = 1;
            $order->canceled_by = 'user';
            $order->order_total = ProviderCategoryFee::where('provider_id', $order->provider_id)->where('cat_id', $order->cat_id)->select('fee')->first()->fee;
        $order->save();


        $ar_text = 'عذراً,لقد تم إلغاء الطلب من قبل الفني';
        $en_text = 'Sorry,the current order has been canceled by the technician';

        UserNot::create
        (
            [
                'type' => 'push',
                'user_id' => $order->user_id,
                'order_id' => $request->order_id,
                'ar_text' => $ar_text,
                'en_text' => $en_text
            ]
        );


        $token = UserNot::where('user_id', $order->user_id)->pluck('token');
        PushNotify::tech_send($token, $ar_text, $en_text, 'push', $request->order_id);

        return response()->json(msg($request, success(), 'success'));
    }
}
