<?php

namespace App\Http\Controllers\Admin;

use App\Migrations\WareHouse;
use App\Migrations\WareHouseRequest;
use App\Models\Address;
use App\Models\Category;
use App\Models\Collaboration;
use App\Models\Order;
use App\Models\OrderRate;
use App\Models\OrderTechRequest;
use App\Models\Provider;
use App\Models\ProviderAdmin;
use App\Models\ProviderCategoryFee;
use App\Models\ProviderSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use mysql_xdevapi\Exception;

class ProviderController extends Controller
{
    public function index($state)
    {
        if($state == 'active')
        {
            $providers = Provider::where('active' , 1)->paginate(50);
        }
        elseif($state == 'suspended')
        {
            $providers = Provider::where('active' , 0)->paginate(50);
        }

        return view('admin.providers.index', compact('providers'));
    }

    public function search()
    {
        $search = Input::get('search');
        $providers = Provider::where(function ($q) use ($search)
            {
                $q->where('en_name','like','%'.$search.'%');
                $q->orWhere('ar_name','like','%'.$search.'%');
                $q->orWhere('email','like','%'.$search.'%');
            }
        )->paginate(50);

        return view('admin.providers.index', compact('providers','search'));
    }


    public function show($id)
    {
        $provider = Provider::find($id);
        return view('admin.providers.show', compact('provider'));
    }


    public function statistics($id)
    {
        $provider = Provider::find($id);
        $company_ids = Collaboration::where('provider_id',$provider->id)->pluck('company_id');


        $this_month = new Carbon('first day of this month');
        $this_year = new Carbon('first day of january this year');

        $monthly_orders = Order::raw('table orders select * from orders')->where('provider_id', $provider->id)->whereIn('company_id', $company_ids)->where('created_at','>=', $this_month->toDateTimeString())->get();
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

        $yearly_orders = Order::raw('table orders select * from orders')->where('provider_id', $provider->id)->whereIn('company_id', $company_ids)->where('created_at','>=', $this_year->toDateTimeString())->get();
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

        return view('admin.providers.statistics',
            compact('provider','this_month','this_year','monthly_orders_count','monthly_open','monthly_closed','monthly_canceled','monthly_canceled_user','monthly_canceled_tech'
                ,'monthly_revenue','monthly_parts_orders','monthly_parts_orders_count','monthly_parts_count','monthly_parts_prices','monthly_rate_commitment','monthly_rate_appearance','monthly_rate_cleanliness'
                ,'monthly_rate_performance','yearly_orders_count','yearly_open','yearly_closed','yearly_canceled','yearly_canceled_user','yearly_canceled_tech','yearly_revenue','yearly_parts_orders','yearly_parts_orders_count'
                ,'yearly_parts_count','yearly_parts_prices','yearly_rate_commitment','yearly_rate_cleanliness','yearly_rate_performance','yearly_rate_appearance'
            )
        );
    }


    public function create()
    {
        $addresses = Address::where('parent_id', NULL)->get();
        return view('admin.providers.single', compact('addresses'));
    }


    public function store(Request $request)
    {
        $this->validate($request,
            [
                'address_id' => 'required',
                'interest_fee' => 'required|integer',
                'warehouse_fee' => 'required|integer',
                'ar_name' => 'required|unique:providers,ar_name',
                'en_name' => 'required|unique:providers,en_name',
                'ar_desc' => 'required',
                'en_desc' => 'required',
                'email' => 'required|email|unique:providers,email',
                'phones' => 'required|array',
                'logo' => 'required|image',
                'username' => 'required|unique:provider_admins,username',
                'password' => 'required|min:6|confirmed',
                'mobile' => 'required|unique:provider_admins,phone',
                'badge_id' => 'required'
            ],
            [
                'address_id.required' => 'Address is required',
                'interest_fee.required' => 'Interest Fee is required',
                'warehouse_fee.required' => 'Warehouse Fee is required',
                'ar_name.required' => 'Arabic name is required',
                'ar_name.unique' => 'Arabic name already exists',
                'en_name.required' => 'English name is required',
                'en_name.unique' => 'English name already exists',
                'ar_desc.required' => 'Arabic description is required',
                'en_desc.required' => 'English description is required',
                'email.required' => 'Email is required',
                'email.unique' => 'Email already exists',
                'phones.required' => 'Phones are required',
                'logo.required' => 'Logo is required',
                'logo.image' => 'Logo is not valid',
                'username.required' => 'Username is required',
                'username.unique' => 'Username already exists',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be 6 digits at minimum',
                'password.confirmed' => 'Password and its confirmation does not match',
                'mobile.required' => 'Admin Mobile is required',
                'mobile.unique' => 'Admin Mobile already exists',
                'badge_id.required' => 'Admin Badge ID is required',
            ]
        );


        $image = unique_file($request->logo->getClientOriginalName());
        $image1 = unique_file($request->logo->getClientOriginalName());

        $request->logo->move(base_path().'/public/providers/logos/', $image);

        if(!File::exists(base_path().'/public/providers/admins'))
        {
            File::makeDirectory(base_path().'/public/providers/admins');
        }

        File::copy(base_path().'/public/providers/logos/'.$image,base_path().'/public/providers/admins/'.$image1);

        $provider = Provider::create(
            [
                'address_id' => $request->address_id,
                'interest_fee' => $request->interest_fee,
                'warehouse_fee' => $request->warehouse_fee,
                'ar_name' => $request->ar_name,
                'en_name' => $request->en_name,
                'ar_desc' => $request->ar_desc,
                'en_desc' => $request->en_desc,
                'email' => $request->email,
                'phones' => serialize(array_filter($request->phones)),
                'logo' => $image
            ]
        );

        $admin = ProviderAdmin::create(
            [
                'provider_id' => $provider->id,
                'badge_id' => $request->badge_id,
                'role' => 'system_admin',
                'name' => $provider->en_name,
                'email' => $provider->email,
                'phone' => $request->mobile,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'image' => $image1
            ]
        );

        $admin->assignRole('provider_system_admin');

        WareHouse::Up($provider->id);
        WareHouseRequest::Up($provider->id);

        $subs = Category::where('type', 2)->pluck('price','id');

        foreach($subs as $id => $fee)
        {
            ProviderCategoryFee::create
            (
                [
                    'provider_id' => $provider->id,
                    'cat_id' => $id,
                    'fee' => $fee
                ]
            );
        }

        return redirect('/admin/providers/active')->with('success', 'Provider added successfully !');
    }


    public function edit($id)
    {
        $provider = Provider::where('id', $id)->with('address')->first();
        $addresses = Address::where('parent_id', NULL)->get();

        return view('admin.providers.single', compact('provider', 'addresses'));
    }


    public function update(Request $request)
    {
        $admin = ProviderAdmin::where('provider_id', $request->provider_id)->first();
        $request->merge(['admin_id' => $admin->id]);

        $this->validate($request,
            [
                'provider_id' => 'required|exists:providers,id',
                'address_id' => 'sometimes',
                'interest_fee' => 'required|numeric',
                'warehouse_fee' => 'required|numeric',
                'ar_name' => 'required|unique:providers,ar_name,'.$request->provider_id,
                'en_name' => 'required|unique:providers,en_name,'.$request->provider_id,
                'ar_desc' => 'required',
                'en_desc' => 'required',
                'email' => 'required|email|unique:providers,email,'.$request->provider_id,
                'phones' => 'required|array',
                'logo' => 'sometimes|image',
                'username' => 'required|unique:provider_admins,username,'.$request->admin_id,
                'password' => 'sometimes|confirmed'
            ],
            [
                'address_id.required' => 'Address is required',
                'interest_fee.required' => 'Interest Fee is required',
                'interest_fee.numeric' => 'Interest Fee is not a number',
                'warehouse_fee.required' => 'Warehouse Fee is required',
                'warehouse_fee.numeric' => 'Warehouse Fee is not a number',
                'ar_name.required' => 'Arabic name is required',
                'ar_name.unique' => 'Arabic name already exists',
                'en_name.required' => 'English name is required',
                'en_name.unique' => 'English name already exists',
                'ar_desc.required' => 'Arabic description is required',
                'en_desc.required' => 'English description is required',
                'email.required' => 'Email is required',
                'email.unique' => 'Email already exists',
                'phones.required' => 'Phones are required',
                'logo.image' => 'Logo is not valid',
                'username.required' => 'Username is required',
                'username.unique' => 'Username already exists',
                'password.required' => 'Password is required',
                'password.confirmed' => 'Password does not match',
            ]
        );

        $provider = Provider::where('id', $request->provider_id)->first();
                if($request->address_id) $provider->address_id = $request->address_id;
                $provider->interest_fee = $request->interest_fee;
                $provider->warehouse_fee = $request->warehouse_fee;
                $provider->ar_name = $request->ar_name;
                $provider->en_name = $request->en_name;
                $provider->ar_desc = $request->ar_desc;
                $provider->en_desc = $request->en_desc;
                $provider->email = $request->email;
                $provider->phones = serialize(array_filter($request->phones));
                if($request->logo)
                {
                    $image = unique_file($request->logo->getClientOriginalName());
                    $request->logo->move(base_path().'/public/providers/logos/', $image);
                    $provider->logo = $image;
                }
        $provider->save();


            $admin->username = $request->username;
            if($request->password) $admin->password = Hash::make($request->password);
        $admin->save();

        if($provider->active == 1) $text = 'active';
        else $text = 'suspended';

        return redirect('/admin/providers/'.$text)->with('success', 'Provider added successfully !');
    }


    public function change_state(Request $request)
    {
        $this->validate($request,
            [
                'provider_id' => 'required|exists:providers,id',
                'state' => 'required|in:0,1',
            ]
        );

        $provider = Provider::find($request->provider_id);
            $provider->active = $request->state;
        $provider->save();

        if($provider->active == 1)
        {
            return back()->with('success', 'Provider activated successfully !');
        }
        else
        {
            return back()->with('success', 'Provider suspended successfully !');
        }
    }


    public function destroy(Request $request)
    {
        $this->validate($request,
            [
                'provider_id' => 'required|exists:providers,id',
            ]
        );

        $provider = Provider::where('id',$request->provider_id)->first();

        if($provider->active == 1)
        {
            $provider->delete();
            WareHouse::Down($request->provider_id);
            WareHouseRequest::Down($request->provider_id);

            return redirect('/admin/providers/active')->with('success', 'Provider deleted successfully !');
        }
        elseif($provider->active == 0)
        {
            $provider->delete();
            WareHouse::Down($request->provider_id);
            WareHouseRequest::Down($request->provider_id);

            return redirect('/admin/providers/suspended')->with('success', 'Provider deleted successfully !');
        }
    }


    public function change_password(Request $request)
    {
        $this->validate($request,
            [
                'provider_id' => 'required|exists:providers,id',
                'password' => 'required|min:6|confirmed'
            ],
            [
                'password.required' => 'Password is required',
                'password.min' => 'Password must be 6 digits at minimum',
                'password.confirmed' => 'Password and its confirmation does not match'
            ]
        );

        $provider = Provider::find($request->provider_id);
            $provider->password = Hash::make($request->pasword);
        $provider->save();

        return back()->with('success', 'Password has been changed successfully !');
    }


    public function get_subscriptions($provider_id)
    {
        $provider = Provider::find($provider_id);
        $subscriptions = ProviderSubscription::where('provider_id', $provider_id)->first();

        if(isset($subscriptions))
        {
            $subs = unserialize($subscriptions->subs);
        }
        else
        {
            $subs = [];
        }

        $cats = Category::where('parent_id', NULL)->get();

        return view('admin.providers.subscriptions', compact('provider','subs','cats'));
    }


    public function set_subscriptions(Request $request)
    {
        $this->validate($request,
            [
                'provider_id' => 'required|exists:providers,id',
                'subs' => 'required|array',
                'subs.*' => 'exists:categories,id,type,2'
            ]
        );

        ProviderSubscription::updateOrCreate
        (
            [
                'provider_id' => $request->provider_id
            ],
            [
                'subs' => serialize($request->subs)
            ]
        );

        foreach($request->subs as $sub)
        {
            $check =  ProviderCategoryFee::where('provider_id', $request->provider_id)->where('cat_id', $sub)->first();

            if(! $check)
            {
                $fee = Category::where('id', $sub)->select('price')->first()->price;

                $cat_fee = new ProviderCategoryFee();
                    $cat_fee->provider_id = $request->provider_id;
                    $cat_fee->cat_id = $sub;
                    $cat_fee->fee = $fee;
                $cat_fee->save();
            }
        }

        ProviderCategoryFee::where('provider_id', $request->provider_id)->whereNotIn('cat_id', $request->subs)->delete();

        $state = Provider::find($request->provider_id)->active;

        if($state == 0)
        {
            $state = 'suspended';
        }
        else
        {
            $state = 'active';
        }

        return redirect('/admin/providers/'.$state)->with('success', 'Subscriptions have been set successfully !');
    }
}
