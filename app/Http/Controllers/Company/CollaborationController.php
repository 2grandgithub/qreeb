<?php

namespace App\Http\Controllers\Company;

use App\Models\Category;
use App\Models\Collaboration;
use App\Models\CompanySubscription;
use App\Models\Order;
use App\Models\OrderRate;
use App\Models\OrderTechRequest;
use App\Models\Provider;
use App\Models\ProviderCategoryFee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CollaborationController extends Controller
{
    public function index()
    {
        $providers = Collaboration::where('company_id', company()->company_id)->paginate(50);
        foreach($providers as $provider)
        {
            $provider['orders'] = $provider->orders_count($provider->provider_id,company()->company_id);
        }

        return view('company.collaborations.index', compact('providers'));
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
                'collaboration_id' => 'required|exists:collaborations,id,company_id,'.company()->company_id
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

        return view('company.collaborations.statistics',
            compact('collaboration','this_month','this_year','monthly_orders_count','monthly_open','monthly_closed','monthly_canceled','monthly_canceled_user','monthly_canceled_tech'
                ,'monthly_revenue','monthly_parts_orders','monthly_parts_orders_count','monthly_parts_count','monthly_parts_prices','monthly_rate_commitment','monthly_rate_appearance','monthly_rate_cleanliness'
                ,'monthly_rate_performance','yearly_orders_count','yearly_open','yearly_closed','yearly_canceled','yearly_canceled_user','yearly_canceled_tech','yearly_revenue','yearly_parts_orders','yearly_parts_orders_count'
                ,'yearly_parts_count','yearly_parts_prices','yearly_rate_commitment','yearly_rate_cleanliness','yearly_rate_performance','yearly_rate_appearance'
            )
        );
    }


    public function orders_request($coll_id)
    {
        $provider_id = Collaboration::where('id', $coll_id)->select('provider_id')->first()->provider_id;
        $provider_ids = Collaboration::where('company_id', company()->company_id)->pluck('provider_id');
        $providers = Provider::whereIn('id', $provider_ids)->select('id','en_name')->get();

        return view('company.collaborations.orders_info_request', compact('coll_id','provider_id','providers'));
    }


    public function orders_show(Request $request)
    {
        $this->validate($request,
            [
                'coll_id' => 'required|exists:collaborations,id,company_id,'.company()->company_id,
                'provider_id' => 'required|exists:collaborations,provider_id,company_id,'.company()->company_id,
                'from' => 'required|date',
                'to' => 'required|date'
            ],
            [
                'provider_id.required' => 'Please choose a provider',
                'provider_id.exists' => 'Invalid Provider',
                'from.required' => 'Please choose a date to start from',
                'from.date' => 'Please choose a valid date to start from',
                'to.required' => 'Please choose a date to end with',
                'to.date' => 'Please choose a valid date to end with',
            ]
        );

        $sub_cats = Order::where('company_id', company()->company_id)->where('provider_id', $request->provider_id)->where('created_at','>=',$request->from)->where('created_at','<=',$request->to)->distinct()->pluck('cat_id');

        $cats = new Collection();

        foreach($sub_cats as $cat)
        {
            $parent_id = Category::where('id', $cat)->select('parent_id')->first()->parent_id;
            $parent = Category::where('id', $parent_id)->select('id','en_name as name')->first();;

            $cats = $cats->push($parent);
        }


        foreach($cats as $cat)
        {
            $subs = Category::where('parent_id', $cat->id)->pluck('id');


            $all_orders = Order::where('company_id', company()->company_id)->where('provider_id', $request->provider_id)->where('created_at','>=',$request->from)->where('created_at','<=',$request->to)->whereIn('cat_id', $subs)->select('type','cat_id','order_total')->get();


            $cat['urgent'] = $all_orders->where('type', 'urgent')->count();
            $cat['scheduled'] = $all_orders->where('type', 'scheduled')->count();
            $cat['re_scheduled'] = $all_orders->where('type', 're_scheduled')->count();
            $cat['quantity'] = $all_orders->count();
            $cat['rates'] = $all_orders->sum('order_total');

            unset($cat->id);
        }


        $cats[] = collect(['total' => $cats->sum('rates')]);
        $provider = Provider::where('id', $request->provider_id)->select('en_name as name')->first();
        $from = $request->from;
        $to = $request->to;
        $coll_id = $request->coll_id;

        return view('company.collaborations.orders_info_show', compact('coll_id','cats','provider','from','to'));
    }


    public function orders_export(Request $request)
    {
        $this->validate($request,
            [
                'coll_id' => 'required|exists:collaborations,id,company_id,'.company()->company_id,
                'from' => 'required|date',
                'to' => 'required|date'
            ]
        );

        $provider_id = Collaboration::where('id', $request->coll_id)->first()->provider_id;
        $provider = Provider::where('id', $provider_id)->select('id','en_name')->first();

        $sub_cats = Order::where('company_id', company()->company_id)->where('provider_id', $provider->id)->where('created_at','>=',$request->from)->where('created_at','<=',$request->to)->distinct()->pluck('cat_id');
        $cats = new Collection();

        foreach($sub_cats as $cat)
        {
            $parent_id = Category::where('id', $cat)->select('parent_id')->first()->parent_id;
            $parent = Category::where('id', $parent_id)->select('id','en_name as Name')->first();;

            $cats = $cats->push($parent);
        }


        foreach($cats as $cat)
        {
            $subs = Category::where('parent_id', $cat->id)->pluck('id');


            $all_orders = Order::where('company_id', company()->company_id)->where('provider_id', $provider->id)->where('created_at','>=',$request->from)->where('created_at','<=',$request->to)->whereIn('cat_id', $subs)->select('type','cat_id','order_total')->get();


            $cat['Urgent'] = $all_orders->where('type', 'urgent')->count();
            $cat['Scheduled'] = $all_orders->where('type', 'scheduled')->count();
            $cat['Re_scheduled'] = $all_orders->where('type', 're_scheduled')->count();
            $cat['Quantity'] = $all_orders->count();
            $cat['Rates'] = $all_orders->sum('order_total');
            $cat['Total'] = '';
            unset($cat->id);
        }


        $cats[] = collect(['Name' => '-','Urgent' => '-','Scheduled' => '-','Re_scheduled' => '-','Quantity' => '-','Rates' => '-','Total' => $cats->sum('Rates')]);

        $cats = $cats->toArray();
        $from = $request->from;
        $to = $request->to;
        $p_name = str_replace(' ','-',$provider->en_name);

        $filename = 'qareeb_'.$p_name.'_'.$from.'_'.$to.'_orders_invoice.xls';

        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");

        $heads = false;
        foreach($cats as $cat)
        {
            if($heads == false)
            {
                echo implode("\t", array_keys($cat)) . "\n";
                $heads = true;
            }
            {
                echo implode("\t", array_values($cat)) . "\n";
            }
        }

        die();
    }


    public function fees_show($provider_id,Request $request)
    {
        $request->merge(['provider_id' => $provider_id]);

        $this->validate($request,
            [
                'provider_id' => 'required|exists:collaborations,provider_id,company_id,'.company()->company_id
            ]
        );

        $cats = CompanySubscription::where('company_id', company()->company_id)->select('subs')->first()->subs;
        $cats = unserialize($cats);

        $subs = ProviderCategoryFee::where('provider_id', $request->provider_id)->whereIn('cat_id', $cats)->select('cat_id','fee')->get();
        $provider = Provider::where('id', $request->provider_id)->select('id','en_name as name')->first();

        return view('company.collaborations.fees_info_show', compact('subs','provider'));
    }


    public function fees_export($provider_id,Request $request)
    {
        $request->merge(['provider_id' => $provider_id]);

        $this->validate($request,
            [
                'provider_id' => 'required|exists:collaborations,provider_id,company_id,'.company()->company_id
            ]
        );

        $cats = CompanySubscription::where('company_id', company()->company_id)->select('subs')->first()->subs;
        $cats = unserialize($cats);

        $subs = ProviderCategoryFee::where('provider_id', $request->provider_id)->whereIn('cat_id', $cats)->select('cat_id','fee')->get();

        $provider = Provider::where('id', $request->provider_id)->select('en_name as name')->first();

        foreach($subs as $sub)
        {
            $cat = Category::where('id', $sub->cat_id)->select('parent_id','en_name')->first();
            $parent = Category::where('id', $cat->parent_id)->select('en_name')->first();

            $sub['Category'] = $parent->en_name .' - '. $cat->en_name;
            $sub['Fee'] = $sub->fee;

            unset($sub->cat_id,$sub->fee);
        }


        $subs = $subs->toArray();
        $p_name = str_replace(' ','-',$provider->name);

        $filename = 'qareeb_'.$p_name.'_categories_fees.xls';


        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");

        $heads = false;
        foreach($subs as $sub)
        {
            if($heads == false)
            {
                echo implode("\t", array_keys($sub)) . "\n";
                $heads = true;
            }
            {
                echo implode("\t", array_values($sub)) . "\n";
            }
        }

        die();
    }
}
