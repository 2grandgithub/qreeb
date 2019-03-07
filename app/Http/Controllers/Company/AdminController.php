<?php

namespace App\Http\Controllers\Company;

use App\Models\CompanyAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index($type, Request $request)
    {
        $request->merge(['type' => $type]);

        $this->validate($request,
            [
                'type' => 'required|in:owners,system_admins,app_managers,users_managers,service_desks,users'
            ]
        );

        if($type == 'owners')
        {
            $slogan = 'Owners'; $role = 'owner';
        }
        elseif($type == 'system_admins')
        {
            $slogan = 'System Admins'; $role = 'system_admin';
        }
        elseif($type == 'app_managers')
        {
            $slogan = 'App Managers'; $role = 'app_manager';
        }
        elseif($type == 'users_managers')
        {
            $slogan = 'Users Managers'; $role = 'users_manager';
        }
        elseif($type == 'service_desks')
        {
            $slogan = 'Service Desks'; $role = 'service_desk';
        }
        elseif($type == 'users')
        {
            $slogan = 'Users'; $role = 'user';
        }

        $admins = CompanyAdmin::where('company_id', company()->company_id)->where('role',$role)->paginate(50);

        return view('company.admins.index', compact('admins','slogan','role','type'));
    }


    public function search()
    {
        $search = Input::get('search');
        $admins = CompanyAdmin::where('company_id', company()->company_id)->where(function ($q) use ($search)
        {
            $q->where('name','like','%'.$search.'%');
            $q->orWhere('email','like','%'.$search.'%');
            $q->orWhere('phone','like','%'.$search.'%');
        }
        )->paginate(50);

        foreach($admins as $admin)
        {
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
        }

        return view('company.admins.search', compact('admins','search'));
    }


    public function create()
    {
        $roles = Role::where('guard_name', 'company')->get();

        foreach($roles as $role)
        {
            if($role->name == 'company_owner') $slogan = 'Owner';
            elseif($role->name == 'company_system_admin') $slogan = 'System Admin';
            elseif($role->name == 'company_app_manager') $slogan = 'App Manager';
            elseif($role->name == 'company_users_manager') $slogan = 'Users Manager';
            elseif($role->name == 'company_service_desk') $slogan = 'Service Desk';
            elseif($role->name == 'company_user') $slogan = 'User';

            $role['slogan'] = $slogan;
        }

        $permissions['company_owner'] = ['General Statistics','Financial Statistics','Company Observe','Sub Companies Observe','Users Observe','Orders Observe','Items Requests Observe'];
        $permissions['company_system_admin'] = ['Admins','General Statistics','Financial Statistics','Company Observe','Company Operate','Sub Companies Observe','Sub Companies Operate','Collaborations Observe','Collaborations Operate','Users Observe','Users Files Upload','Users Operate','Orders Observe','Items Requests Observe','ItemsRequests Operate'];
        $permissions['company_app_manager'] = ['General Statistics','Financial Statistics','Company Observe','Company Operate','Sub Companies Observe','Collaborations Observe','Collaborations Operate','Users Observe','Users Operate','Users Files Upload','Orders Observe','Items Requests Observe','ItemsRequests Operate'];
        $permissions['company_users_manager'] = ['General Statistics','Financial Statistics','Company Observe','Sub Companies Observe','Sub Companies Operate','Collaborations Observe','Collaborations Operate','Users Observe','Users Files Upload','Orders Observe','Items Requests Observe'];
        $permissions['company_service_desk'] = ['General Statistics','Company Observe','Sub Companies Observe','Collaborations Observe','Collaborations Operate','Users Observe','Orders Observe','Items Requests Observe'];
        $permissions['company_user'] = ['General Statistics','Company Observe','Sub Companies Observe','Collaborations Observe','Users Observe','Orders Observe','Items Requests Observe'];

        return view('company.admins.single', compact('roles','permissions'));
    }


    public function store(Request $request)
    {
        $this->validate($request,
            [
                'role' => 'required|in:company_owner,company_system_admin,company_app_manager,company_users_manager,company_service_desk,company_user',
                'badge_id' => 'required',
                'name' => 'required',
                'email' => 'required|unique:company_admins,email',
                'phone' => 'required|unique:company_admins,phone',
                'image' => 'sometimes|image',
                'username' => 'required|unique:company_admins,username',
                'password' => 'required|confirmed'
            ],
            [
                'role.required' => 'Role is required',
                'badge_id.required' => 'Badge ID is required',
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.unique' => 'Email already exists,please choose another one',
                'phone.required' => 'Phone is required',
                'phone.unique' => 'Phone already exists,please choose another one',
                'image.image' => 'Image is invalid',
                'username.required' => 'Username is required',
                'username.unique' => 'Username already exists,please choose another one',
                'password.required' => 'Password is required',
                'password.confirmed' => 'Password does not match'
            ]
        );

        $badge_check = User::where('badge_id', $request->badge_id)->where('company_id',company()->company_id)->first();

        if($badge_check)
        {
            return back()->with('error', 'Sorry,this Badge ID already exists');
        }

        $admin = new CompanyAdmin();
            $admin->role = str_replace('company_','',$request->role);
            $admin->company_id = company()->company_id;
            $admin->badge_id = $request->badge_id;
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            $admin->username = $request->username;
            $admin->password = Hash::make($request->password);
            if($request->image)
            {
                $name = unique_file($request->image->getClientOriginalName());
                $request->image->move(base_path().'/public/companies/admins',$name);
                $admin->image = $name;
            }
        $admin->save();

        $admin->assignRole('company_'.$admin->role);

        return redirect('/company/admins/'.$admin->role.'s/index')->with('success', 'Admin created successfully!');
    }


    public function show($admin_id, Request $request)
    {
        $request->merge(['admin_id' => $admin_id]);

        $this->validate($request,
            [
                'admin_id' => 'required|exists:company_admins,id,company_id,'.company()->company_id
            ]
        );

        $admin = CompanyAdmin::find($request->admin_id);

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

        return view('company.admins.show', compact('admin'));
    }


    public function edit($admin_id, Request $request)
    {
        $request->merge(['admin_id' => $admin_id]);

        $this->validate($request,
            [
                'admin_id' => 'required|exists:company_admins,id'
            ]
        );

        $roles = Role::where('guard_name', 'company')->get();

        foreach($roles as $role)
        {
            if($role->name == 'company_owner') $slogan = 'Owner';
            elseif($role->name == 'company_system_admin') $slogan = 'System Admin';
            elseif($role->name == 'company_app_manager') $slogan = 'App Manager';
            elseif($role->name == 'company_users_manager') $slogan = 'Users Manager';
            elseif($role->name == 'company_service_desk') $slogan = 'Service Desk';
            elseif($role->name == 'company_user') $slogan = 'User';

            $role['slogan'] = $slogan;
        }

        $admin = CompanyAdmin::find($request->admin_id);

        $permissions['company_owner'] = ['General Statistics','Financial Statistics','Company Observe','Sub Companies Observe','Users Observe','Orders Observe','Items Requests Observe'];
        $permissions['company_system_admin'] = ['Admins','General Statistics','Financial Statistics','Company Observe','Company Operate','Sub Companies Observe','Sub Companies Operate','Collaborations Observe','Collaborations Operate','Users Observe','Users Files Upload','Users Operate','Orders Observe','Items Requests Observe','ItemsRequests Operate'];
        $permissions['company_app_manager'] = ['General Statistics','Financial Statistics','Company Observe','Company Operate','Sub Companies Observe','Collaborations Observe','Collaborations Operate','Users Observe','Users Operate','Users Files Upload','Orders Observe','Items Requests Observe','ItemsRequests Operate'];
        $permissions['company_users_manager'] = ['General Statistics','Financial Statistics','Company Observe','Sub Companies Observe','Sub Companies Operate','Collaborations Observe','Collaborations Operate','Users Observe','Users Files Upload','Orders Observe','Items Requests Observe'];
        $permissions['company_service_desk'] = ['General Statistics','Company Observe','Sub Companies Observe','Collaborations Observe','Collaborations Operate','Users Observe','Orders Observe','Items Requests Observe'];
        $permissions['company_user'] = ['General Statistics','Company Observe','Sub Companies Observe','Collaborations Observe','Users Observe','Orders Observe','Items Requests Observe'];

        return view('company.admins.single', compact('admin', 'roles','permissions'));
    }


    public function update(Request $request)
    {

        $this->validate($request,
            [
                'admin_id' => 'required|exists:company_admins,id',
                'role' => 'required|in:company_owner,company_system_admin,company_app_manager,company_users_manager,company_service_desk,company_user',
                'badge_id' => 'required',
                'name' => 'required',
                'email' => 'required|unique:company_admins,email,'.$request->admin_id,
                'phone' => 'required|unique:company_admins,phone,'.$request->admin_id,
                'image' => 'sometimes|image',
            ],
            [
                'role.required' => 'Role is required',
                'badge_id.required' => 'RoBadge _ID le is required',
                'en_name.required' => 'English Name is required',
                'ar_name.required' => 'Arabic Name is required',
                'email.required' => 'Email is required',
                'email.unique' => 'Email already exists,please choose another one',
                'phone.required' => 'Phone is required',
                'phone.unique' => 'Phone already exists,please choose another one',
                'image.image' => 'Image is invalid'
            ]
        );

        $badge_check = User::where('badge_id', $request->badge_id)->where('company_id',company()->company_id)->where('id','!=',$request->user_id)->first();

        if($badge_check)
        {
            return back()->with('error', 'Sorry,this Badge ID already exists');
        }

        $admin = CompanyAdmin::find($request->admin_id);
            $admin->role = str_replace('company_','',$request->role);
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            if($request->image)
            {
                $name = unique_file($request->image->getClientOriginalName());
                $request->image->move(base_path().'/public/companies/admins/',$name);
                $admin->image = $name;
            }
        $admin->save();

        $admin->syncRoles(['company_'.$admin->role]);

        return redirect('/company/admins/'.$admin->role.'s/index')->with('success', 'Admin updated successfully!');
    }


    public function destroy(Request $request)
    {
        $this->validate($request,
            [
                'admin_id' => 'required|exists:company_admins,id'
            ]
        );

        CompanyAdmin::where('id', $request->admin_id)->delete();

        return back()->with('success', 'Admin deleted successfully !');
    }


    public function change_status(Request $request)
    {
        $this->validate($request,
            [
                'admin_id' => 'required|exists:company_admins,id',
                'state' => 'required|in:0,1'
            ]
        );

        CompanyAdmin::where('id', $request->admin_id)->update(['active' => $request->state]);

        if($request->state == 1 ) return back()->with('success', 'Admin activated successfully !');
        else return back()->with('success', 'Admin suspended successfully !');
    }
    
//    public function test()
//    {
////        $arr = ['statistics_general','statistics_financial','companies_operate','collaborations_observe','collaborations_operate'];
////
////        foreach($arr as $a)
////        {
////            Permission::create(['name' => 'companies_operate','guard_name' => 'company']);
////        }
////
////        dd('g');
//        $roles = Role::where('guard_name','company')->get();
//
//        foreach($roles as $role)
//        {
//            if($role->name == 'company_owner')
//            {
//                $ps = ['statistics_general','statistics_financial','companies_observe','sub_companies_observe','users_observe','orders_observe','items_requests_observe'];
//                $role->givePermissionTo($ps);
//            }
//            if($role->name == 'company_system_admin')
//            {
//                $ps = ['admins','statistics_general','statistics_financial','companies_observe','companies_operate','sub_companies_observe','sub_companies_operate','collaborations_observe','collaborations_operate','users_observe','file_upload','users_operate','orders_observe','items_requests_observe','items_requests_operate'];
//                $role->givePermissionTo($ps);
//            }
//            if($role->name == 'company_app_manager')
//            {
//                $ps = ['statistics_general','statistics_financial','companies_observe','sub_companies_observe','collaborations_observe','collaborations_operate','users_observe','file_upload','users_operate','orders_observe','items_requests_observe'];
//                $role->givePermissionTo($ps);
//            }
//            if($role->name == 'company_users_manager')
//            {
//                $ps = ['statistics_general','companies_observe','sub_companies_observe','collaborations_observe','collaborations_operate','users_observe','file_upload','users_operate','orders_observe','items_requests_observe'];
//                $role->givePermissionTo($ps);
//            }
//            if($role->name == 'company_service_desk')
//            {
//                $ps = ['statistics_general','companies_observe','sub_companies_observe','collaborations_observe','collaborations_operate','users_observe','orders_observe','items_requests_observe'];
//                $role->givePermissionTo($ps);
//            }
//            if($role->name == 'company_user')
//            {
//                $ps = ['statistics_general','companies_observe','sub_companies_observe','collaborations_observe','users_observe','orders_observe','items_requests_observe'];
//                $role->givePermissionTo($ps);
//            }
//        }
//
//        dd('sdfds');
////        $permissions['company_owner'] = ['General Statistics','Financial Statistics','Company Observe','Sub Companies Observe','Users Observe','Orders Observe','Items Requests Observe'];
////        $permissions['company_system_admin'] = ['Admins','General Statistics','Financial Statistics','Company Observe','Company Operate','Sub Companies Observe','Sub Companies Operate','Collaborations Observe','Collaborations Operate','Users Observe','Users Files Upload','Users Operate','Orders Observe','Items Requests Observe','ItemsRequests Operate'];
////        $permissions['company_app_manager'] = ['General Statistics','Financial Statistics','Company Observe','Company Operate','Sub Companies Observe','Collaborations Observe','Collaborations Operate','Users Observe','Users Operate','Users Files Upload','Orders Observe','Items Requests Observe','ItemsRequests Operate'];
////        $permissions['company_users_manager'] = ['General Statistics','Financial Statistics','Company Observe','Sub Companies Observe','Sub Companies Operate','Collaborations Observe','Collaborations Operate','Users Observe','Users Files Upload','Orders Observe','Items Requests Observe'];
////        $permissions['company_service_desk'] = ['General Statistics','Company Observe','Sub Companies Observe','Collaborations Observe','Collaborations Operate','Users Observe','Orders Observe','Items Requests Observe'];
////        $permissions['company_user'] = ['General Statistics','Company Observe','Sub Companies Observe','Collaborations Observe','Users Observe','Orders Observe','Items Requests Observe'];
//
//    }
}
