<?php

namespace App\Http\Controllers\Provider;

use App\Models\Collaboration;
use App\Models\Order;
use App\Models\OrderRate;
use App\Models\OrderTechRequest;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CollaborationController extends Controller
{
    public function index()
    {
        $companies = Collaboration::where('provider_id', provider()->provider_id)->paginate(50);
        foreach($companies as $company)
        {
            $company['orders'] = $company->orders_count(provider()->provider_id,$company->company_id);
        }

        return view('provider.collaborations.index', compact('companies'));
    }


    public function statistics($id, Request $request)
    {
        $request->merge(
            [
            'collaboration_id' => $id
            ]
        );

        $this->validate($request,
            [
                'collaboration_id' => 'required|exists:collaborations,id,provider_id,'.provider()->provider_id
            ]
        );

        $collaboration = Collaboration::find($id);


        $this_month = new Carbon('first day of this month');
        $this_year = new Carbon('first day of january this year');

        $monthly_orders = Order::raw('table orders')->where('provider_id', $collaboration->provider_id)->where('company_id', $collaboration->company_id)->where('created_at','>=', $this_month->toDateTimeString())->get();
        $monthly_orders_ids = $monthly_orders->pluck('id');

        $monthly_orders_count = $monthly_orders->count();
        $monthly_open = $monthly_orders->where('completed', 0)->count();
        $monthly_closed = $monthly_orders->where('completed', 1)->count();
        $monthly_canceled = $monthly_orders->where('canceled', 1)->count();
        $monthly_canceled_user = $monthly_orders->where('canceled', 1)->where('canceled_by', 'user')->count();
        $monthly_canceled_tech = $monthly_orders->where('canceled', 1)->where('canceled_by', 'tech')->count();
        $monthly_revenue = $monthly_orders->sum('order_total');

        $monthly_parts_orders = $monthly_orders->where('type','re_scheduled');
        $monthly_parts_orders_count = $monthly_parts_orders->count();
        $monthly_parts = OrderTechRequest::whereIn('order_id', $monthly_parts_orders->pluck('id'));
        $monthly_parts_count = $monthly_parts->count();
        $monthly_parts_data = $monthly_parts->select('item_id','provider_id')->get();

        $monthly_arr= [];
        foreach($monthly_parts_data as $part)
        {
            $price = DB::table($part->provider_id.'_warehouse_parts')->where('id', $part->item_id)->select('price')->first()->price;
            array_push($monthly_arr, $price);
        }
        $monthly_parts_prices = array_sum($monthly_arr);

        $monthly_rates_ids = OrderRate::whereIn('order_id', $monthly_orders_ids)->get();
        $monthly_rate_commitment = (string)round($monthly_rates_ids->pluck('commitment')->avg(),0);
        $monthly_rate_cleanliness = (string)round($monthly_rates_ids->pluck('cleanliness')->avg(),0);
        $monthly_rate_performance = (string)round($monthly_rates_ids->pluck('performance')->avg(),0);
        $monthly_rate_appearance = (string)round($monthly_rates_ids->pluck('appearance')->avg(),0);

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $yearly_orders = Order::raw('table orders')->where('provider_id', $collaboration->provider_id)->where('company_id', $collaboration->company_id)->where('created_at','>=', $this_year->toDateTimeString())->get();
        $yearly_orders_ids = $yearly_orders->pluck('id');

        $yearly_orders_count = $yearly_orders->count();
        $yearly_open = $yearly_orders->where('completed', 0)->count();
        $yearly_closed = $yearly_orders->where('completed', 1)->count();
        $yearly_canceled = $yearly_orders->where('canceled', 1)->count();
        $yearly_canceled_user = $yearly_orders->where('canceled', 1)->where('canceled_by', 'user')->count();
        $yearly_canceled_tech = $yearly_orders->where('canceled', 1)->where('canceled_by', 'tech')->count();
        $yearly_revenue = $yearly_orders->sum('order_total');

        $yearly_parts_orders = $yearly_orders->where('type','re_scheduled');
        $yearly_parts_orders_count = $yearly_parts_orders->count();
        $yearly_parts = OrderTechRequest::whereIn('order_id', $yearly_parts_orders->pluck('id'));
        $yearly_parts_count = $yearly_parts->count();
        $yearly_parts_data = $yearly_parts->select('item_id','provider_id')->get();

        $yearly_arr= [];
        foreach($yearly_parts_data as $part)
        {
            $price = DB::table($part->provider_id.'_warehouse_parts')->where('id', $part->item_id)->select('price')->first()->price;
            array_push($yearly_arr, $price);
        }
        $yearly_parts_prices = array_sum($yearly_arr);

        $yearly_rates_ids = OrderRate::whereIn('order_id', $yearly_orders_ids)->get();
        $yearly_rate_commitment = (string)round($yearly_rates_ids->pluck('commitment')->avg(),0);
        $yearly_rate_cleanliness = (string)round($yearly_rates_ids->pluck('cleanliness')->avg(),0);
        $yearly_rate_performance = (string)round($yearly_rates_ids->pluck('performance')->avg(),0);
        $yearly_rate_appearance = (string)round($yearly_rates_ids->pluck('appearance')->avg(),0);

        return view('provider.collaborations.statistics',
            compact('collaboration','this_month','this_year','monthly_orders_count','monthly_open','monthly_closed','monthly_canceled','monthly_canceled_user','monthly_canceled_tech'
            ,'monthly_revenue','monthly_parts_orders','monthly_parts_orders_count','monthly_parts_count','monthly_parts_prices','monthly_rate_commitment','monthly_rate_appearance','monthly_rate_cleanliness'
            ,'monthly_rate_performance','yearly_orders_count','yearly_open','yearly_closed','yearly_canceled','yearly_canceled_user','yearly_canceled_tech','yearly_revenue','yearly_parts_orders','yearly_parts_orders_count'
            ,'yearly_parts_count','yearly_parts_prices','yearly_rate_commitment','yearly_rate_cleanliness','yearly_rate_performance','yearly_rate_appearance'
            )
        );
    }
}
