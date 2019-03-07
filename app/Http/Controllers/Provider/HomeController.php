<?php

namespace App\Http\Controllers\Provider;

use App\Models\Address;
use App\Models\Category;
use App\Models\Collaboration;
use App\Models\Order;
use App\Models\OrderRate;
use App\Models\OrderTechRequest;
use App\Models\Provider;
use App\Models\ProviderAdmin;
use App\Models\ProviderSubscription;
use App\Models\Technician;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    public function dashboard()
    {
        $company_ids = Collaboration::where('provider_id',provider()->provider_id)->pluck('company_id');


        $this_month = new Carbon('first day of this month');
        $this_year = new Carbon('first day of january this year');

        $monthly_orders = Order::raw('table orders')->where('provider_id', provider()->provider_id)->whereIn('company_id', $company_ids)->where('created_at','>=', $this_month->toDateTimeString())->get();
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

        $yearly_orders = Order::raw('table orders')->where('provider_id', provider()->provider_id)->whereIn('company_id', $company_ids)->where('created_at','>=', $this_year->toDateTimeString())->get();
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

        return view('provider.dashboard',
            compact('collaboration','this_month','this_year','monthly_orders_count','monthly_open','monthly_closed','monthly_canceled','monthly_canceled_user','monthly_canceled_tech'
                ,'monthly_revenue','monthly_parts_orders','monthly_parts_orders_count','monthly_parts_count','monthly_parts_prices','monthly_rate_commitment','monthly_rate_appearance','monthly_rate_cleanliness'
                ,'monthly_rate_performance','yearly_orders_count','yearly_open','yearly_closed','yearly_canceled','yearly_canceled_user','yearly_canceled_tech','yearly_revenue','yearly_parts_orders','yearly_parts_orders_count'
                ,'yearly_parts_count','yearly_parts_prices','yearly_rate_commitment','yearly_rate_cleanliness','yearly_rate_performance','yearly_rate_appearance'
            )
        );
    }


    public function profile()
    {
        $admin = provider();

        if($admin->role == 'owner')
        {
            $admin['role'] = 'Owner';
        }
        elseif($admin->role == 'system_admin')
        {
            $admin['role'] = 'System Admin';
        }
        elseif($admin->role == 'app_manager')
        {
            $admin['role'] = 'App Manager';
        }
        elseif($admin->role == 'users_manager')
        {
            $admin['role'] = 'Users Manager';
        }
        elseif($admin->role == 'service_desk')
        {
            $admin['role'] = 'Service Desk';
        }
        elseif($admin->role == 'user')
        {
            $admin['role'] = 'User';
        }

        return view('provider.profile.admin', compact('admin'));
    }


    public function update_profile(Request $request)
    {
        $this->validate($request,
            [
                'username' => 'required|unique:provider_admins,username,'.provider()->id,
                'name' => 'required',
                'email' => 'required|unique:provider_admins,email,'.provider()->id,
                'phone' => 'required|unique:provider_admins,phone,'.provider()->id,
            ]
        );

        $admin = provider();
            $admin->username = $request->username;
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
        $admin->save();

        return back()->with('success', 'Info changed successfully !');
    }


    public function change_password(Request $request)
    {
        $this->validate($request,
            [
                'password' => 'required|min:6|confirmed',
            ]
        );

        $admin = provider();
            $admin->password = Hash::make($request->password);
        $admin->save();

        return back()->with('success', 'Password changed successfully !');
    }


    public function my_provider()
    {
        $provider = Provider::find(provider()->provider_id);
        return view('provider.profile.show', compact('provider'));
    }


    public function info()
    {
        $addresses = Address::where('parent_id', NULL)->get();
        $provider = Provider::find(provider()->provider_id);

        return view('provider.profile.info', compact('provider','addresses'));
    }


    public function update_info(Request $request)
    {
        $this->validate($request,
            [
                'address_id' => 'sometimes|exists:addresses,id',
                'ar_name' => 'required|unique:providers,ar_name,'.provider()->provider_id,
                'en_name' => 'required|unique:providers,en_name,'.provider()->provider_id,
                'ar_desc' => 'required',
                'en_desc' => 'required',
                'email' => 'required|email|unique:providers,email,'.provider()->provider_id,
                'phones' => 'required|array',
                'logo' => 'sometimes|image'
            ]
        );

        $provider = Provider::find(provider()->provider_id);
            if($request->address_id) $provider->address_id = $request->address_id;
            $provider->en_name = $request->en_name;
            $provider->ar_name = $request->ar_name;
            $provider->en_desc = $request->en_desc;
            $provider->ar_desc = $request->ar_desc;
            $provider->phones = serialize(array_filter($request->phones));
            if($request->logo)
            {
                unlink(base_path().'/public/providers/logos/'.$provider->logo);

                $name = unique_file($request->logo->getClientOriginalName());
                $request->logo->move(base_path().'/public/providers/logos/',$name);
                $provider->logo = $name;
            }
        $provider->save();

        return back()->with('success', 'Info changed successfully !');
    }


    public function get_cities($parent)
    {
        $cities = Address::where('parent_id', $parent)->get();
        return response()->json($cities);
    }


    public  function get_sub_cats($parent)
    {
        $subscriptions = ProviderSubscription::where('provider_id', provider()->provider_id)->first()->subs;
        $subs = unserialize($subscriptions);

        $sub_cats = Category::where('parent_id', $parent)->whereIn('id', $subs)->with('parent')->get();
        return response()->json($sub_cats);
    }
}
