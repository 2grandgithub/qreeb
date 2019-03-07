<?php

namespace App\Http\Controllers\Admin;

use App\Models\Address;
use App\Models\Category;
use App\Models\Collaboration;
use App\Models\Company;
use App\Models\CompanyAdmin;
use App\Models\CompanySubscription;
use App\Models\Order;
use App\Models\OrderRate;
use App\Models\OrderTechRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index($state)
    {
        if($state == 'active')
        {
            $companies = Company::where('active' , 1)->paginate(50);
        }
        elseif($state == 'suspended')
        {
            $companies = Company::where('active' , 0)->paginate(50);
        }

        return view('admin.companies.index', compact('companies'));
    }


    public function search()
    {
        $search = Input::get('search');
        $companies = Company::where(function ($q) use ($search)
            {
                $q->where('en_name','like','%'.$search.'%');
                $q->orWhere('ar_name','like','%'.$search.'%');
                $q->orWhere('email','like','%'.$search.'%');
            }
        )->paginate(50);

        return view('admin.companies.index', compact('companies','search'));
    }


    public function create()
    {
        $countries = Address::where('parent_id', NULL)->get();
        $categories = Category::where('parent_id', NULL)->get();
        return view('admin.companies.single', compact('countries','categories'));
    }


    public function show($id)
    {
        $company = Company::find($id);
        return view('admin.companies.show', compact('company'));
    }


    public function statistics($id)
    {
        $company = Company::find($id);
        $provider_ids = Collaboration::where('company_id',$company->id)->pluck('provider_id');


        $this_month = new Carbon('first day of this month');
        $this_year = new Carbon('first day of january this year');

        $monthly_orders = Order::raw('table orders select * from orders')->whereIn('provider_id', $provider_ids)->where('company_id', $company->id)->where('created_at','>=', $this_month->toDateTimeString())->get();
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

        $yearly_orders = Order::raw('table orders select * from orders')->whereIn('provider_id', $provider_ids)->where('company_id', $company->id)->where('created_at','>=', $this_year->toDateTimeString())->get();
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

        return view('admin.companies.statistics',
            compact('company','this_month','this_year','monthly_orders_count','monthly_open','monthly_closed','monthly_canceled','monthly_canceled_user','monthly_canceled_tech'
                ,'monthly_revenue','monthly_parts_orders','monthly_parts_orders_count','monthly_parts_count','monthly_parts_prices','monthly_rate_commitment','monthly_rate_appearance','monthly_rate_cleanliness'
                ,'monthly_rate_performance','yearly_orders_count','yearly_open','yearly_closed','yearly_canceled','yearly_canceled_user','yearly_canceled_tech','yearly_revenue','yearly_parts_orders','yearly_parts_orders_count'
                ,'yearly_parts_count','yearly_parts_prices','yearly_rate_commitment','yearly_rate_cleanliness','yearly_rate_performance','yearly_rate_appearance'
            )
        );
    }


    public function store(Request $request)
    {
        $this->validate($request,
            [
                'address_id' => 'required|exists:addresses,id',
                'interest_fee' => 'required|numeric',
                'ar_name' => 'required|unique:companies,ar_name',
                'en_name' => 'required|unique:companies,en_name',
                'ar_desc' => 'required',
                'en_desc' => 'required',
                'email' => 'required|email|unique:companies,email',
                'phones' => 'required',
                'logo' => 'required|image',
                'item_limit' => 'required',
                'username' => 'required|unique:company_admins',
                'password' => 'required|confirmed|min:6',
                'mobile' => 'required|unique:provider_admins,phone',
                'badge_id' => 'required'
            ],
            [
                'address_id.required' => 'Address is required',
                'interest_fee.required' => 'Interest Fee is required',
                'interest_fee.numeric' => 'Interest Fee is not a number',
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
                'item_limit.required' => 'Order Item Limit is required',
                'username.required' => 'Username is required',
                'username.exists' => 'Username already exists',
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

        $request->logo->move(base_path().'/public/companies/logos/', $image);

        if(!File::exists(base_path().'/public/companies/admins'))
        {
            File::makeDirectory(base_path().'/public/companies/admins');
        }

        File::copy(base_path().'/public/companies/logos/'.$image,base_path().'/public/companies/admins/'.$image1);

        $company = Company::create(
            [
                'address_id' => $request->address_id,
                'interest_fee' => $request->interest_fee,
                'ar_name' => $request->ar_name,
                'en_name' => $request->en_name,
                'ar_desc' => $request->ar_desc,
                'en_desc' => $request->en_desc,
                'email' => $request->email,
                'phones' => serialize(array_filter($request->phones)),
                'logo' => $image,
                'item_limit' => $request->item_limit
            ]
        );

        $admin = CompanyAdmin::create(
            [
                'company_id' => $company->id,
                'badge_id' => $request->badge_id,
                'role' => 'system_admin',
                'name' => $company->en_name,
                'email' => $company->email,
                'phone' => $request->mobile,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'image' => $image1
            ]
        );

        $admin->assignRole('company_system_admin');

        return redirect('/admin/companies/active')->with('success', 'Company added successfully !');
    }


    public function edit($id)
    {
        $company = Company::find($id);
        $countries = Address::where('parent_id', NULL)->get();
        $categories = Category::where('parent_id', NULL)->get();
        return view('admin.companies.single', compact('company','countries','categories'));
    }


    public function update(Request $request)
    {
        $admin = CompanyAdmin::where('company_id', $request->company_id)->first();
        $request->merge(['admin_id' => $admin->id]);

        $this->validate($request,
            [
                'company_id' => 'required|exists:companies,id',
                'interest_fee' => 'required|numeric',
                'address_id' => 'sometimes|exists:addresses,id',
                'ar_name' => 'required|unique:companies,ar_name,'.$request->company_id,
                'en_name' => 'required|unique:companies,en_name,'.$request->company_id,
                'ar_desc' => 'required',
                'en_desc' => 'required',
                'email' => 'required|email|unique:companies,email,'.$request->company_id,
                'phones' => 'required',
                'logo' => 'sometimes|image',
                'item_limit' => 'required',
                'username' => 'required|unique:company_admins,username,'.$request->admin_id,
                'password' => 'sometimes|confirmed'
            ],
            [
                'interest_fee.required' => 'Interest Fee is required',
                'interest_fee.numeric' => 'Interest Fee is not a number',
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
                'item_limit.required' => 'Order Item Limit is required',
                'username.required' => 'Username is required',
                'username.unique' => 'Username already exists',
                'password.required' => 'Password is required',
                'password.confirmed' => 'Password does not match',
            ]
        );


        $company = Company::where('id',$request->company_id)->first();
                if($request->address_id) $company->address_id = $request->address_id;
                $company->interest_fee = $request->interest_fee;
                $company->ar_name = $request->ar_name;
                $company->en_name = $request->en_name;
                $company->ar_desc = $request->ar_desc;
                $company->en_desc = $request->en_desc;
                $company->email = $request->email;
                $company->phones = serialize(array_filter($request->phones));
                $company->item_limit = $request->item_limit;
                if($request->logo)
                {
                    $image = unique_file($request->logo->getClientOriginalName());
                    $request->logo->move(base_path().'/public/companies/logos/', $image);
                    $company->logo = $image;
                }
        $company->save();


            $admin->username = $request->username;
            if($request->password) $admin->password = Hash::make($request->password);
        $admin->save();

        if($company->active == 1) $text = 'active';
        else $text = 'suspended';

        return redirect('/admin/companies/'.$text)->with('success', 'Company updated successfully');
    }


    public function destroy(Request $request)
    {
        $this->validate($request,
            [
                'company_id' => 'required|exists:companies,id',
            ]
        );

        $company = Company::where('id',$request->company_id)->first();

        if($company->active == 1)
        {
            $company->delete();
            return redirect('/admin/companies/active')->with('success', 'Company deleted successfully !');
        }
        elseif($company->active == 0)
        {
            $company->delete();
            return redirect('/admin/companies/suspended')->with('success', 'Company deleted successfully !');
        }
    }


    public function change_state(Request $request)
    {
        $this->validate($request,
            [
                'company_id' => 'required|exists:companies,id',
                'state' => 'required|in:0,1',
            ]
        );

        $company = Company::find($request->company_id);
        $company->active = $request->state;
        $company->save();

        if($company->active == 1)
        {
            return back()->with('success', 'Company activated successfully !');
        }
        else
        {
            return back()->with('success', 'Company suspended successfully !');
        }
    }


    public function get_subscriptions($company_id)
    {
        $company = Company::find($company_id);
        $subscriptions = CompanySubscription::where('company_id', $company_id)->first();

        if(isset($subscriptions))
        {
            $subs = unserialize($subscriptions->subs);
        }
        else
        {
            $subs = [];
        }

        $cats = Category::where('parent_id', NULL)->get();

        return view('admin.companies.subscriptions', compact('company','subs','cats'));
    }


    public function set_subscriptions(Request $request)
    {
        $this->validate($request,
            [
                'company_id' => 'required|exists:companies,id',
                'subs' => 'required|array',
            ]
        );

        CompanySubscription::updateOrCreate
        (
            [
                'company_id' => $request->company_id
            ],
            [
                'subs' => serialize($request->subs)
            ]
        );

        $state = Company::find($request->company_id)->active;

        if($state == 0)
        {
            $state = 'suspended';
        }
        else
        {
            $state = 'active';
        }

        return redirect('/admin/companies/'.$state)->with('success', 'Subscriptions have been set successfully !');
    }
}
