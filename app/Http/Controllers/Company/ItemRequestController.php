<?php

namespace App\Http\Controllers\Company;

use App\Models\ItemRequestState;
use App\Models\Order;
use App\Models\OrderTechRequest;
use App\Models\Provider;
use App\Models\ProviderCategoryFee;
use App\Models\PushNotify;
use App\Models\User;
use App\Models\UserNot;
use App\Models\UserToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ItemRequestController extends Controller
{
    public function index($status)
    {
        $items = ItemRequestState::where('company_id', company()->company_id)->where('status', $status)->paginate(50);
        foreach($items as $item)
        {
            $item['provider'] = Provider::where('id', $item->provider_id)->first()->en_name;
            $item['requested'] = OrderTechRequest::where('id', $item->request_id)->select('id','order_id','status','item_id')->first();
            $item['item_data'] = DB::table($item->provider_id.'_warehouse_parts')->where('id', $item->requested->item_id)->select('en_name','price','image')->first();
            $item['user_name'] = Order::where('id', $item->requested->order_id)->select('id','user_id')->first()->user->en_name;
        }

        return view('company.warehouse_request.index', compact('items'));
    }


    public function search()
    {
        $search = Input::get('search');
        $user = User::where('company_id', company()->company_id)->where(function($q) use($search)
            {
                $q->where('en_name','like','%'.$search.'%');
                $q->orWhere('ar_name','like','%'.$search.'%');
                $q->orWhere('email','like','%'.$search.'%');
                $q->orWhere('phone','like','%'.$search.'%');
            }
        )->first();

        if($user)
        {
            $order_ids = Order::where('user_id', $user->id)->pluck('id');
        }
        else
        {
            $order_ids = Order::where('user_id', 0)->pluck('id');
        }

        $request_ids = OrderTechRequest::whereIn('order_id', $order_ids)->pluck('id');

        $items = ItemRequestState::where('company_id', company()->company_id)->whereIn('request_id', $request_ids)->paginate(50);

        foreach($items as $item)
        {
            if($item->type == 'awaiting') $type = 'Awaiting';
            elseif($item->type == 'confirmed') $type = 'Confirmed';
            else $type = 'Declined';

            $item['status'] = $type;
            $item['provider'] = Provider::where('id', $item->provider_id)->first()->en_name;
            $item['requested'] = OrderTechRequest::where('id', $item->request_id)->select('id','order_id','status','item_id')->first();
            $item['item_data'] = DB::table($item->provider_id.'_warehouse_parts')->where('id', $item->requested->item_id)->select('en_name','price','image')->first();
            $item['user_name'] = Order::where('id', $item->requested->order_id)->select('id','user_id')->first()->user->en_name;
        }

        return view('company.warehouse_request.search', compact('items','search'));
    }


    public function change_status(Request $request)
    {
        $this->validate($request,
            [
                'state_id' => 'required|exists:item_request_states,id',
                'status' => 'required|in:confirmed,declined'
            ]
        );

        $status = ItemRequestState::where('id', $request->state_id)->first();
            $status->status = $request->status;
        $status->save();

        $tech_request = OrderTechRequest::where('id', $status->request_id)->first();
            $tech_request->status = $status->status;
        $tech_request->save();

        $order_id = OrderTechRequest::where('id', $status->request_id)->select('order_id')->first()->order_id;
        $order = Order::where('id', $order_id)->select('id','user_id','cat_id','item_total','order_total','completed')->first();

        if($status->status == 'confirmed')
        {
                $item = DB::table($status->provider_id.'_warehouse_parts')->where('id', $tech_request->item_id)->first();
                $count = $item->count;

                DB::table($status->provider_id.'_warehouse_parts')->where('id', $tech_request->item_id)->update(['count' => $count - 1]);

                $item_price = $item->price;
                $order->item_total =  $item_price + $order->item_total;
                $order->order_total = $item_price + $order->order_total;
                $order->save();

                $other_requests = OrderTechRequest::where('order_id', $order_id)->pluck('status');

                if(in_array('awaiting', $other_requests->toArray()) == false)
                {

                    $ar_text = 'تم الموافقة علي جميع الطلبات الخاصة بقطع الصيانة للطب خاصتك,الرجاء تحديد موعد';
                    $en_text = 'All parts requests for your order is confirmed,please pick a date to revisit';

                    UserNot::create
                    (
                        [
                            'type' => 'time',
                            'user_id' => $order->user_id,
                            'order_id' => $order->id,
                            'ar_text' => $ar_text,
                            'en_text' => $en_text
                        ]
                    );

                    $token = UserToken::where('user_id', $order->user_id)->pluck('token');
                    PushNotify::user_send($token,$ar_text,$en_text,'time',$order->id);
                }
                else
                {
                    $ar_text = 'تم الموافقة علي قطعة مطلوبة للطلب خاصتك';
                    $en_text = 'An item request for your order item is approved';

                    UserNot::create
                    (
                        [
                            'type' => 'order',
                            'user_id' => $order->user_id,
                            'order_id' => $order->id,
                            'ar_text' => $ar_text,
                            'en_text' => $en_text
                        ]
                    );

                    $token = UserToken::where('user_id', $order->user_id)->pluck('token');
                    PushNotify::user_send($token,$ar_text,$en_text,'order',$order->id);
                }
        }
        elseif($status->status == 'declined')
        {
            $ar_text = 'تم رفض طلب علي قطعة مطلوبة للطلب خاصتك,الرجاء المتابعة مع الإدارة';
            $en_text = 'An item request for your order item is declined,please contact the administration';

            $order->completed = 1;
            $order->save();

            UserNot::create
            (
                [
                    'type' => 'order',
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'ar_text' => $ar_text,
                    'en_text' => $en_text
                ]
            );

            $token = UserToken::where('user_id', $order->user_id)->pluck('token');
            PushNotify::user_send($token,$ar_text,$en_text,'order',$order->id);
        }


        return back()->with('success', 'Request approved successfully !');
    }
}
