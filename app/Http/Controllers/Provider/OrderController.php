<?php

namespace App\Http\Controllers\Provider;

use App\Models\Order;
use App\Models\Provider;
use App\Models\Technician;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class OrderController extends Controller
{
    public function index($type)
    {
        if($type == 'canceled')
        {
            $orders = Order::where('company_id', provider()->provider_id)->where('canceled', 1)->paginate(50);
        }
        else
        {
            $orders = Order::where('provider_id', provider()->provider_id)->where('type', $type)->paginate(50);
        }


        if($type == 'urgent') $new_type = 'Urgent';
        elseif($type == 'scheduled') $new_type = 'Scheduled';
        elseif($type == 're_scheduled')  $new_type = 'Re-Scheduled';
        elseif($type == 'canceled')  $new_type = 'Canceled';

        return view('provider.orders.index', compact('orders','type','new_type'));
    }


    public function search()
    {
        $search = Input::get('search');
        $tech = Technician::where('provider_id', provider()->provider_id)->where(function($q) use($search)
            {
                $q->where('en_name','like','%'.$search.'%');
                $q->orWhere('ar_name','like','%'.$search.'%');
                $q->orWhere('email','like','%'.$search.'%');
                $q->orWhere('phone','like','%'.$search.'%');
                $q->orWhere('badge_id', $search);
            }
        )->first();

        $orders = new Collection();

        if($tech)
        {
            $by_tech = Order::where('tech_id', $tech->id)->paginate(50);
            foreach($by_tech as $order)
            {
                if($order->type == 'urgent') $type = 'Urgent';
                elseif($order->type == 'scheduled') $type = 'Scheduled';
                else $type = 'Re Scheduled';

                $order['type'] = $type;
            }

            $orders = $orders->merge($by_tech);
        }

        $by_smo = Order::where('provider_id', provider()->provider_id)->where('smo', $search)->paginate(50);

        $orders = $orders->merge($by_smo);

        return view('provider.orders.search', compact('orders','search'));
    }


    public function show($id,Request $request)
    {
        $request->merge(['order_id' => $id]);
        $this->validate($request,
            [
                'order_id' => 'required|exists:orders,id,provider_id,'.provider()->provider_id
            ]
        );

        $order = Order::find($id);

        return view('provider.orders.show', compact('order'));
    }


    public function orders_request($type, Request $request)
    {
        $request->merge(['type' => $type]);
        $this->validate($request,
            [
                'type' => 'required|in:urgent,scheduled,re_scheduled,canceled'
            ]
        );

        $types['urgent'] = 'Urgent';
        $types['scheduled'] = 'Scheduled';
        $types['re_scheduled'] = 'Re-scheduled';
        $types['canceled'] = 'Canceled';

        return view('provider.orders.orders_request', compact('type','types'));
    }


    public function orders_show(Request $request)
    {
        $this->validate($request,
            [
                'type' => 'in:urgent,scheduled,re_scheduled,canceled',
                'from' => 'required|date',
                'to' => 'required|date'
            ],
            [
                'type.required' => 'Please choose a type',
                'type.exists' => 'Invalid Type',
                'from.required' => 'Please choose a date to start from',
                'from.date' => 'Please choose a valid date to start from',
                'to.required' => 'Please choose a date to end with',
                'to.date' => 'Please choose a valid date to end with',
            ]
        );

        if($request->type == 'canceled')
        {
            $orders = Order::where('provider_id', provider()->provider_id)->where('canceled', 1)->where('created_at','>=',$request->from)->where('created_at','<=',$request->to)->get();
        }
        else
        {
            $orders = Order::where('provider_id', provider()->provider_id)->where('type', $request->type)->where('created_at','>=',$request->from)->where('created_at','<=',$request->to)->get();
        }
        $orders[] = collect(['total' => $orders->sum('order_total')]);

        if($request->type == 'urgent') $type_key = 'urgent' ; $type_value = 'Urgent';
        if($request->type == 'scheduled') $type_key = 'scheduled' ; $type_value = 'Scheduled';
        if($request->type == 're_scheduled') $type_key = 're_scheduled' ; $type_value = 'Re-Scheduled';
        if($request->type == 'canceled') $type_key = 'canceled' ; $type_value = 'Canceled';

        $from = $request->from;
        $to = $request->to;

        return view('provider.orders.orders_show', compact('orders','type_key','type_value','from','to'));
    }


    public function orders_export(Request $request)
    {
        $this->validate($request,
            [
                'type' =>'in:urgent,scheduled,re_scheduled,canceled',
                'from' => 'required|date',
                'to' => 'required|date'
            ]
        );


        $orders = new Collection();
        if($request->type == 'canceled')
        {
            $get_orders = Order::where('provider_id', provider()->provider_id)->where('canceled', 1)->where('created_at','>=',$request->from)->where('created_at','<=',$request->to)->get();
        }
        else
        {
            $get_orders = Order::where('provider_id', provider()->provider_id)->where('type', $request->type)->where('created_at','>=',$request->from)->where('created_at','<=',$request->to)->get();
        }

        foreach($get_orders as $order)
        {
            if($order->type == 'urgent') $type = 'Urgent';
            elseif($order->type == 'scheduled') $type = 'Scheduled';
            elseif($order->canceled == 1) $type = 'Canceled';

            $collect['Category'] = $order->category->parent->en_name . ' - ' . $order->category->en_name;
            $collect['Date'] = $order->created_at->toDateTimeString();
            $collect['Type'] = $type;
            if($order->type == 'canceled') $collect['By'] = $order->canceled_by;
            $collect['Revenue'] = $order->order_total;
            $collect['Total'] = '';

            $orders = $orders->push($collect);
        }


        if($request->type == 'canceled')
        {
            $orders[] = collect(['Category' => '-','Date' => '-','Type' => '-','By' => '-','Cost' => '-','Total' => $orders->sum('Revenue')]);
        }
        else
        {
            $orders[] = collect(['Category' => '-','Date' => '-','Type' => '-','Cost' => '-','Total' => $orders->sum('Revenue')]);
        }

        if($get_orders->count() > 0)
        {
            $orders = $orders->toArray();
            $provider = Provider::where('id', provider()->provider_id)->select('en_name')->first();
            $from = $request->from;
            $to = $request->to;
            $p_name = str_replace(' ','-',$provider->en_name);

            $filename = 'qareeb_'.$p_name.'_'.$type.'_'.$from.'_'.$to.'_orders_invoice.xls';

            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");

            $heads = false;
            foreach($orders as $order)
            {
                if($heads == false)
                {
                    echo implode("\t", array_keys($order)) . "\n";
                    $heads = true;
                }
                {
                    echo implode("\t", array_values($order)) . "\n";
                }
            }

            die();
        }
        else
        {
            return redirect('/provider/orders/'.$request->type)->with('error', 'No Result !');
        }

    }
}
