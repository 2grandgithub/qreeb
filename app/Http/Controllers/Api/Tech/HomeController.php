<?php

namespace App\Http\Controllers\Api\Tech;

use App\Models\AboutUs;
use App\Models\Order;
use App\Models\OrderRate;
use App\Models\Technician;
use App\Models\TechNot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index(Request $request)
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

        $orders = Order::where('tech_id', $request->tech_id)->where('completed', 0)->select('id','type','user_id','completed','canceled','canceled_by','scheduled_at','created_at')->latest()->get();

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


    public function about_us($lang)
    {
        $text = AboutUs::select($lang.'_text as text')->first();
        return response()->json($text);
    }


    public function notifications(Request $request)
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
        $nots = TechNot::where('tech_id', $request->tech_id)->select('id','seen','type','order_id',$lang.'_text as text','created_at')->latest()->get();

        return response()->json($nots);
    }


    public function seen(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,'.$request->tech_id,
                'not_id' => 'required|exists:tech_nots,id,tech_id,'.$request->tech_id,
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        TechNot::where('id', $request->not_id)->update(['seen' => 1]);

        return response()->json(['status' => 'success', 'msg' => 'notification is seen successfully']);
    }


    public function rates(Request $request)
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


        $orders = Order::where('tech_id', $request->tech_id)->pluck('id');
        $rates = OrderRate::whereIn('order_id',$orders)->select('appearance','cleanliness','performance','commitment')->get();

        $arr = [];
        if($rates->pluck('appearance')->count() > 0) $arr['appearance'] =  $rates->sum('appearance') / $rates->pluck('appearance')->count(); else $arr['appearance'] = 0;
        if($rates->pluck('cleanliness')->count() > 0) $arr['cleanliness'] = $rates->sum('cleanliness') / $rates->pluck('cleanliness')->count(); else $arr['cleanliness'] = 0;
        if($rates->pluck('performance')->count() > 0) $arr['performance'] = $rates->sum('performance') / $rates->pluck('performance')->count(); else $arr['performance'] = 0;
        if($rates->pluck('commitment')->count() > 0) $arr['commitment'] = $rates->sum('commitment') / $rates->pluck('commitment')->count(); else $arr['commitment'] = 0;

        return response()->json($arr);
    }
}
