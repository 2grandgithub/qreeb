<?php

namespace App\Http\Controllers\Provider;

use App\Models\ProviderAdmin;
use App\Models\Technician;
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
                'type' => 'required|in:owners,system_admins,app_managers,technicians_managers,service_desks,warehouse_desks,users'
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
        elseif($type == 'technicians_managers')
        {
            $slogan = 'Technicians Managers'; $role = 'technicians_managers';
        }
        elseif($type == 'service_desks')
        {
            $slogan = 'Service Desks'; $role = 'service_desk';
        }
        elseif($type == 'warehouse_desks')
        {
            $slogan = 'Warehouse Desks'; $role = 'warehouse_desk';
        }
        elseif($type == 'users')
        {
            $slogan = 'Users'; $role = 'user';
        }

        $admins = ProviderAdmin::where('provider_id', provider()->provider_id)->where('role',$role)->paginate(50);

        return view('provider.admins.index', compact('admins','slogan','role','type'));
    }


    public function search()
    {
        $search = Input::get('search');
        $admins = ProviderAdmin::where('provider_id', provider()->provider_id)->where(function ($q) use ($search)
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
            elseif($admin->role == 'technicians_managers')
            {
                $admin['role'] = 'Technicians Manager';
            }
            elseif($admin->role == 'service_desk')
            {
                $admin['role'] = 'Service Desk';
            }
            elseif($admin->role == 'warehouse_desk')
            {
                $admin['role'] = 'Warehouse Desk';
            }
            elseif($admin->role == 'user')
            {
                $admin['role'] = 'User';
            }
        }

        return view('provider.admins.search', compact('admins','search'));
    }


    public function create()
    {
        $roles = Role::where('guard_name', 'provider')->get();

        foreach($roles as $role)
        {
            if($role->name == 'provider_owner') $slogan = 'Owner';
            elseif($role->name == 'provider_system_admin') $slogan = 'System Admin';
            elseif($role->name == 'provider_app_manager') $slogan = 'App Manager';
            elseif($role->name == 'provider_technicians_manager') $slogan = 'Technicians Manager';
            elseif($role->name == 'provider_service_desk') $slogan = 'Service Desk';
            elseif($role->name == 'provider_warehouse_desk') $slogan = 'Warehouse Desk';
            elseif($role->name == 'provider_user') $slogan = 'User';

            $role['slogan'] = $slogan;
        }

        $permissions['provider_owner'] = ['General Statistics','Financial Statistics','Provider Observe','Technicians Observe','Orders Observe','Warehouse Observe','Warehouse Requests Observe','Rotations Observe'];
        $permissions['provider_system_admin'] = ['Admins','General Statistics','Financial Statistics','Provider Observe','Provider Operate','Services Fees','Collaborations Observe','Collaborations Operate','Technicians Observe','Technicians Files Upload','Technicians Operate','Orders Observe','Warehouse Observe','Warehouse Operate','Warehouse Files Upload','Warehouse Requests Observe','Warehouse Requests Operate','Rotations Observe','Rotations Operate'];
        $permissions['provider_app_manager'] = ['General Statistics','Financial Statistics','Provider Observe','Provider Operate','Services Fees','Collaborations Observe','Collaborations Operate','Technicians Observe','Technicians Files Upload','Technicians Operate','Orders Observe','Warehouse Observe','Warehouse Operate','Warehouse Files Upload','Warehouse Requests Observe','Warehouse Requests Operate','Rotations Observe','Rotations Operate'];
        $permissions['provider_technicians_manager'] = ['General Statistics','Financial Statistics','Provider Observe','Technicians Observe','Technicians Operate','Technicians Files Upload','Orders Observe','Warehouse Observe','Warehouse Requests Observe','Rotations Observe'];
        $permissions['provider_service_desk'] = ['General Statistics','Financial Statistics','Provider Observe','Technicians Observe','Orders Observe','Warehouse Observe','Warehouse Requests Observe','Rotations Observe'];
        $permissions['provider_warehouse_desk'] = ['General Statistics','Financial Statistics','Provider Observe','Technicians Observe','Orders Observe','Warehouse Observe','Warehouse Operate','Warehouse Files Upload','Warehouse Requests Observe','Warehouse Requests Operate','Rotations Observe'];
        $permissions['provider_user'] = ['General Statistics','Financial Statistics','Provider Observe','Technicians Observe','Orders Observe','Warehouse Observe','Warehouse Requests Observe','Rotations Observe'];

        return view('provider.admins.single', compact('roles','permissions'));
    }


    public function store(Request $request)
    {
        $this->validate($request,
            [
                'role' => 'required|in:provider_owner,provider_system_admin,provider_app_manager,provider_technicians_manager,provider_service_desk,provider_warehouse_desk,provider_user',
                'badge_id' => 'required',
                'name' => 'required',
                'email' => 'required|unique:provider_admins,email',
                'phone' => 'required|unique:provider_admins,phone',
                'image' => 'sometimes|image',
                'username' => 'required|unique:provider_admins,username',
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

        $badge_check = Technician::where('badge_id', $request->badge_id)->where('provider_id',provider()->provider_id)->first();

        if($badge_check)
        {
            return back()->with('error', 'Sorry,this Badge ID already exists');
        }

        $admin = new ProviderAdmin();
            $admin->role = str_replace('provider_','',$request->role);
            $admin->provider_id = provider()->provider_id;
            $admin->badge_id = $request->badge_id;
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            $admin->username = $request->username;
            $admin->password = Hash::make($request->password);
            if($request->image)
            {
                $name = unique_file($request->image->getClientOriginalName());
                $request->image->move(base_path().'/public/providers/admins',$name);
                $admin->image = $name;
            }
        $admin->save();

        $admin->assignRole('provider_'.$admin->role);

        return redirect('/provider/admins/'.$admin->role.'s/index')->with('success', 'Admin created successfully!');
    }


    public function show($admin_id, Request $request)
    {
        $request->merge(['admin_id' => $admin_id]);

        $this->validate($request,
            [
                'admin_id' => 'required|exists:provider_admins,id,provider_id,'.provider()->provider_id
            ]
        );

        $admin = ProviderAdmin::find($request->admin_id);

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
        elseif($admin->role == 'technicians_manager')
        {
            $admin['role'] = 'Technicians Manager';
        }
        elseif($admin->role == 'service_desk')
        {
            $admin['role'] = 'Service Desk';
        }
        elseif($admin->role == 'warehouse_desk')
        {
            $admin['role'] = 'Warehouse Desk';
        }
        elseif($admin->role == 'user')
        {
            $admin['role'] = 'User';
        }

        return view('provider.admins.show', compact('admin'));
    }


    public function edit($admin_id, Request $request)
    {
        $request->merge(['admin_id' => $admin_id]);

        $this->validate($request,
            [
                'admin_id' => 'required|exists:provider_admins,id'
            ]
        );

        $roles = Role::where('guard_name', 'provider')->get();

        foreach($roles as $role)
        {
            if($role->name == 'provider_owner') $slogan = 'Owner';
            elseif($role->name == 'provider_system_admin') $slogan = 'System Admin';
            elseif($role->name == 'provider_app_manager') $slogan = 'App Manager';
            elseif($role->name == 'provider_techs_manager') $slogan = 'Technicians Manager';
            elseif($role->name == 'provider_service_desk') $slogan = 'Service Desk';
            elseif($role->name == 'provider_warehouse_desk') $slogan = 'Warehouse Desk';
            elseif($role->name == 'provider_user') $slogan = 'User';

            $role['slogan'] = $slogan;
        }

        $admin = ProviderAdmin::find($request->admin_id);


        $permissions['provider_owner'] = ['General Statistics','Financial Statistics','Provider Observe','Technicians Observe','Orders Observe','Warehouse Observe','Warehouse Requests Observe','Rotations Observe'];
        $permissions['provider_system_admin'] = ['Admins','General Statistics','Financial Statistics','Provider Observe','Provider Operate','Services Fees','Collaborations Observe','Collaborations Operate','Technicians Observe','Technicians Files Upload','Technicians Operate','Orders Observe','Warehouse Observe','Warehouse Operate','Warehouse Files Upload','Warehouse Requests Observe','Warehouse Requests Operate','Rotations Observe','Rotations Operate'];
        $permissions['provider_app_manager'] = ['General Statistics','Financial Statistics','Provider Observe','Provider Operate','Services Fees','Collaborations Observe','Collaborations Operate','Technicians Observe','Technicians Files Upload','Technicians Operate','Orders Observe','Warehouse Observe','Warehouse Operate','Warehouse Files Upload','Warehouse Requests Observe','Warehouse Requests Operate','Rotations Observe','Rotations Operate'];
        $permissions['provider_technicians_manager'] = ['General Statistics','Financial Statistics','Provider Observe','Technicians Observe','Technicians Operate','Technicians Files Upload','Orders Observe','Warehouse Observe','Warehouse Requests Observe','Rotations Observe'];
        $permissions['provider_service_desk'] = ['General Statistics','Financial Statistics','Provider Observe','Technicians Observe','Orders Observe','Warehouse Observe','Warehouse Requests Observe','Rotations Observe'];
        $permissions['provider_warehouse_desk'] = ['General Statistics','Financial Statistics','Provider Observe','Technicians Observe','Orders Observe','Warehouse Observe','Warehouse Operate','Warehouse Files Upload','Warehouse Requests Observe','Warehouse Requests Operate','Rotations Observe'];
        $permissions['provider_user'] = ['General Statistics','Financial Statistics','Provider Observe','Technicians Observe','Orders Observe','Warehouse Observe','Warehouse Requests Observe','Rotations Observe'];

        return view('provider.admins.single', compact('admin', 'roles','permissions'));
    }


    public function update(Request $request)
    {

        $this->validate($request,
            [
                'admin_id' => 'required|exists:provider_admins,id',
                'role' => 'required|in:provider_owner,provider_system_admin,provider_app_manager,provider_technicians_manager,provider_service_desk,provider_warehouse_desk,provider_user',
                'badge_id' => 'required',
                'name' => 'required',
                'email' => 'required|unique:provider_admins,email,'.$request->admin_id,
                'phone' => 'required|unique:provider_admins,phone,'.$request->admin_id,
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

        $badge_check = Technician::where('badge_id', $request->badge_id)->where('provider_id',provider()->provider_id)->where('id','!=',$request->user_id)->first();

        if($badge_check)
        {
            return back()->with('error', 'Sorry,this Badge ID already exists');
        }

        $admin = ProviderAdmin::find($request->admin_id);
            $admin->role = str_replace('provider_','',$request->role);
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            if($request->image)
            {
                $name = unique_file($request->image->getClientOriginalName());
                $request->image->move(base_path().'/public/providers/admins/',$name);
                $admin->image = $name;
            }
        $admin->save();

        $admin->syncRoles(['provider_'.$admin->role]);

        return redirect('/provider/admins/'.$admin->role.'s/index')->with('success', 'Admin updated successfully!');
    }


    public function destroy(Request $request)
    {
        $this->validate($request,
            [
                'admin_id' => 'required|exists:provider_admins,id'
            ]
        );

        ProviderAdmin::where('id', $request->admin_id)->delete();

        return back()->with('success', 'Admin deleted successfully !');
    }


    public function change_status(Request $request)
    {
        $this->validate($request,
            [
                'admin_id' => 'required|exists:provider_admins,id',
                'state' => 'required|in:0,1'
            ]
        );

        ProviderAdmin::where('id', $request->admin_id)->update(['active' => $request->state]);

        if($request->state == 1 ) return back()->with('success', 'Admin activated successfully !');
        else return back()->with('success', 'Admin suspended successfully !');
    }
    
    public function test()
    {
//
//        $p = Permission::create
//        (
//            [
//                'guard_name' => 'provider',
//                'name' => 'services_fees'
//            ]
//        );
//
//        $roles = Role::whereIn('name', ['provider_system_admin','provider_app_manager'])->get();
//
//        foreach($roles as $role)
//        {
//            $role->givePermissionTo($p->name);
//        }
//        dd('sdf');
//        $admins = ProviderAdmin::get();
//
//        foreach($admins as $admin)
//        {
//            $admin->syncRoles(['provider_'.$admin->role]);
//        }
//        dd('dd');
//        $roles = Role::where('guard_name','provider')->get();
//
//        foreach($roles as $role)
//        {
//            if($role->name == 'provider_owner')
//            {
//                $ps = ['statistics_general','statistics_financial','providers_observe','techs_observe','orders_observe','warehouse_observe','warehouse_requests_observe','rotations_observe'];
//                $role->givePermissionTo($ps);
//            }
//            if($role->name == 'provider_system_admin')
//            {
//                $ps = ['admins','statistics_general','statistics_financial','providers_observe','providers_operate','collaborations_observe','collaborations_operate','techs_observe','techs_file_upload','techs_operate','orders_observe','warehouse_observe','warehouse_operate','warehouse_file_upload','warehouse_requests_observe','warehouse_requests_operate','rotations_observe','rotations_operate'];
//                $role->givePermissionTo($ps);
//            }
//            if($role->name == 'provider_app_manager')
//            {
//                $ps = ['statistics_general','statistics_financial','providers_observe','providers_operate','collaborations_observe','collaborations_operate','techs_observe','techs_file_upload','techs_operate','orders_observe','warehouse_observe','warehouse_operate','warehouse_file_upload','warehouse_requests_observe','warehouse_requests_operate','rotations_observe','rotations_operate'];
//                $role->givePermissionTo($ps);
//            }
//            if($role->name == 'provider_techs_manager')
//            {
//                $ps = ['statistics_general','providers_observe','collaborations_observe','collaborations_operate','techs_observe','techs_file_upload','techs_operate','orders_observe','warehouse_observe','warehouse_operate','warehouse_file_upload','warehouse_requests_observe','warehouse_requests_operate','rotations_observe','rotations_operate'];
//                $role->givePermissionTo($ps);
//            }
//            if($role->name == 'provider_service_desk')
//            {
//                $ps = ['statistics_general','providers_observe','collaborations_observe','collaborations_operate','techs_observe','orders_observe','warehouse_observe','warehouse_requests_observe','rotations_observe'];
//                $role->givePermissionTo($ps);
//            }
//            if($role->name == 'provider_warehouse_desk')
//            {
//                $ps = ['statistics_general','providers_observe','collaborations_observe','collaborations_operate','techs_observe','orders_observe','warehouse_observe','warehouse_operate','warehouse_file_upload','warehouse_requests_observe','warehouse_requests_operate','rotations_observe'];
//                $role->givePermissionTo($ps);
//            }
//            if($role->name == 'provider_user')
//            {
//                $ps = ['statistics_general','providers_observe','collaborations_observe','techs_observe','orders_observe','warehouse_observe','warehouse_requests_observe','rotations_observe'];
//                $role->givePermissionTo($ps);
//            }
//        }
//
//        dd('sdfds');
////        $permissions['company_owner'] = ['General Statistics','Financial Statistics','Company Observe','Sub Companies Observe','Users Observe','Orders Observe','Items Requests Observe'];
////        $permissions['company_system_admin'] = ['Admins','General Statistics','Financial Statistics','Company Observe','Company Operate','Sub Companies Observe','Sub Companies Operate','Collaborations Observe','Collaborations Operate','Users Observe','Users Files Upload','Users Operate','Orders Observe','Items Requests Observe','ItemsRequests Operate'];
////        $permissions['company_app_manager'] = ['General Statistics','Financial Statistics','Company Observe','Company Operate','Sub Companies Observe','Collaborations Observe','Collaborations Operate','Users Observe','Users Operate','Users Files Upload','Orders Observe','Items Requests Observe','ItemsRequests Operate'];
////        $permissions['company_users_manager'] = ['General Statistics','Financial Statistics','Company Observe','Sub Companies Observe','Sub Companies Operate','Collaborations Observe','Collaborations Operate','Users Observe','Users Files Upload','Orders Observe','Items Requests Observe'];
//        $permissions['company_service_desk'] = ['General Statistics','Company Observe','Sub Companies Observe','Collaborations Observe','Collaborations Operate','Users Observe','Orders Observe','Items Requests Observe'];
//        $permissions['company_user'] = ['General Statistics','Company Observe','Sub Companies Observe','Collaborations Observe','Users Observe','Orders Observe','Items Requests Observe'];

    }
}
