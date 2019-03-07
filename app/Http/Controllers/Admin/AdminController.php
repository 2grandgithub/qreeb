<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

        $admins = Admin::where('role',$role)->paginate(50);

        return view('admin.admins.index', compact('admins','slogan','role','type'));
    }


    public function search()
    {
        $search = Input::get('search');
        $admins = Admin::where(function ($q) use ($search)
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

        return view('admin.admins.search', compact('admins','search'));
    }


    public function create()
    {
        $roles = Role::where('guard_name', 'admin')->get();

        foreach($roles as $role)
        {
            if($role->name == 'owner') $slogan = 'Owner';
            elseif($role->name == 'system_admin') $slogan = 'System Admin';
            elseif($role->name == 'app_manager') $slogan = 'App Manager';
            elseif($role->name == 'users_manager') $slogan = 'Users Manager';
            elseif($role->name == 'service_desk') $slogan = 'Service Desk';
            elseif($role->name == 'user') $slogan = 'User';

            $role['slogan'] = $slogan;
        }

        $permissions['owner'] = ['General Statistics','Financial Statistics','Addresses Observe','Categories Observe','Providers Observe','Providers General Statistics','Providers Financial Statistics', 'Companies Observe','Companies General Statistics','Companies Financial Statistics','Collaborations Observe','Settings Observe'];
        $permissions['system_admin'] = ['Admins','General Statistics','Financial Statistics','Addresses Observe','Addresses Operate','Categories Observe','Categories Operate','Providers General Statistics','Providers Financial Statistics','Providers Subscriptions','Providers Observe','Providers Operate', 'Companies General Statistics','Companies Financial Statistics','Companies Subscriptions','Companies Observe','Companies Operate','Collaborations Observe','Collaborations Operate', 'Settings Observe','Settings Operate'];
        $permissions['app_manager'] = ['General Statistics','Financial Statistics','Addresses Observe','Addresses Operate','Categories Observe','Categories Operate','Providers General Statistics','Providers Financial Statistics','Providers Subscriptions','Providers Observe','Providers Operate', 'Companies General Statistics','Companies Financial Statistics','Companies Subscriptions','Companies Observe','Companies Operate','Collaborations Observe','Collaborations Operate', 'Settings Observe','Settings Operate'];
        $permissions['users_manager'] = ['General Statistics','Addresses Observe','Categories Observe','Providers Observe','Companies Observe','Collaborations Observe','Settings Observe'];
        $permissions['service_desk'] = ['General Statistics','Addresses Observe','Categories Observe','Providers Observe','Companies Observe','Collaborations Observe','Settings Observe'];
        $permissions['user'] = ['Addresses Observe','Categories Observe','Providers Observe','Companies Observe','Collaborations Observe','Settings Observe'];

        return view('admin.admins.single', compact('roles','permissions'));
    }


    public function store(Request $request)
    {
        $this->validate($request,
            [
                'role' => 'required|in:owner,system_admin,app_manager,users_manager,service_desk,user',
                'name' => 'required',
                'email' => 'required|unique:admins,email',
                'phone' => 'required|unique:admins,phone',
                'image' => 'sometimes|image',
                'password' => 'required|confirmed'
            ],
            [
                'role.required' => 'Role is required',
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.unique' => 'Email already exists,please choose another one',
                'phone.required' => 'Phone is required',
                'phone.unique' => 'Phone already exists,please choose another one',
                'image.image' => 'Image is invalid',
                'password.required' => 'Password is required',
                'password.confirmed' => 'Password does not match'
            ]
        );

        $admin = new Admin();
            $admin->role = $request->role;
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            $admin->password = Hash::make($request->password);
            if($request->image)
            {
                $name = unique_file($request->image->getClientOriginalName());
                $request->image->move(base_path().'/public/qareeb_admins',$name);
                $admin->image = $name;
            }
        $admin->save();

        $admin->assignRole($admin->role);

        return redirect('/admin/admins/'.$admin->role.'s/index')->with('success', 'Admin created successfully!');
    }


    public function show($admin_id, Request $request)
    {
        $request->merge(['admin_id' => $admin_id]);

        $this->validate($request,
            [
                'admin_id' => 'required|exists:admins,id'
            ]
        );

        $admin = Admin::find($request->admin_id);

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

        return view('admin.admins.show', compact('admin'));
    }


    public function edit($admin_id, Request $request)
    {
        $request->merge(['admin_id' => $admin_id]);

        $this->validate($request,
            [
                'admin_id' => 'required|exists:admins,id'
            ]
        );

        $roles = Role::where('guard_name', 'admin')->get();

        foreach($roles as $role)
        {
            if($role->name == 'owner') $slogan = 'Owner';
            elseif($role->name == 'system_admin') $slogan = 'System Admin';
            elseif($role->name == 'app_manager') $slogan = 'App Manager';
            elseif($role->name == 'users_manager') $slogan = 'Users Manager';
            elseif($role->name == 'service_desk') $slogan = 'Service Desk';
            elseif($role->name == 'user') $slogan = 'User';

            $role['slogan'] = $slogan;
        }

        $admin = Admin::find($request->admin_id);

        $permissions['owner'] = ['General Statistics','Financial Statistics','Addresses Observe','Categories Observe','Providers Observe','Providers General Statistics','Providers Financial Statistics', 'Companies Observe','Companies General Statistics','Companies Financial Statistics','Collaborations Observe','Settings Observe'];
        $permissions['system_admin'] = ['Admins','General Statistics','Financial Statistics','Addresses Observe','Addresses Operate','Categories Observe','Categories Operate','Providers General Statistics','Providers Financial Statistics','Providers Subscriptions','Providers Observe','Providers Operate', 'Companies General Statistics','Companies Financial Statistics','Companies Subscriptions','Companies Observe','Companies Operate','Collaborations Observe','Collaborations Operate', 'Settings Observe','Settings Operate'];
        $permissions['app_manager'] = ['General Statistics','Financial Statistics','Addresses Observe','Addresses Operate','Categories Observe','Categories Operate','Providers General Statistics','Providers Financial Statistics','Providers Subscriptions','Providers Observe','Providers Operate', 'Companies General Statistics','Companies Financial Statistics','Companies Subscriptions','Companies Observe','Companies Operate','Collaborations Observe','Collaborations Operate', 'Settings Observe','Settings Operate'];
        $permissions['users_manager'] = ['General Statistics','Addresses Observe','Categories Observe','Providers Observe','Companies Observe','Collaborations Observe','Settings Observe'];
        $permissions['service_desk'] = ['General Statistics','Addresses Observe','Categories Observe','Providers Observe','Companies Observe','Collaborations Observe','Settings Observe'];
        $permissions['user'] = ['Addresses Observe','Categories Observe','Providers Observe','Companies Observe','Collaborations Observe','Settings Observe'];

        return view('admin.admins.single', compact('admin', 'roles','permissions'));
    }


    public function update(Request $request)
    {
        $this->validate($request,
            [
                'admin_id' => 'required|exists:admins,id',
                'role' => 'required|in:owner,system_admin,app_manager,users_manager,service_desk,user',
                'name' => 'required',
                'email' => 'required|unique:admins,email,'.$request->admin_id,
                'phone' => 'required|unique:admins,phone,'.$request->admin_id,
                'image' => 'sometimes|image',
            ],
            [
                'role.required' => 'Role is required',
                'en_name.required' => 'English Name is required',
                'ar_name.required' => 'Arabic Name is required',
                'email.required' => 'Email is required',
                'email.unique' => 'Email already exists,please choose another one',
                'phone.required' => 'Phone is required',
                'phone.unique' => 'Phone already exists,please choose another one',
                'image.image' => 'Image is invalid'
            ]
        );

        $admin = Admin::find($request->admin_id);
            $admin->role = $request->role;
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            if($request->image)
            {
                $name = unique_file($request->image->getClientOriginalName());
                $request->image->move(base_path().'/public/qareeb_admins',$name);
                $admin->image = $name;
            }
        $admin->save();

        $admin->syncRoles([$admin->role]);

        return redirect('/admin/admins/'.$admin->role.'s/index')->with('success', 'Admin updated successfully!');
    }


    public function destroy(Request $request)
    {
        $this->validate($request,
            [
                'admin_id' => 'required|exists:admins,id'
            ]
        );

        Admin::where('id', $request->admin_id)->delete();

        return back()->with('success', 'Admin deleted successfully !');
    }


    public function change_status(Request $request)
    {
        $this->validate($request,
            [
                'admin_id' => 'required|exists:admins,id',
                'state' => 'required|in:0,1'
            ]
        );

        Admin::where('id', $request->admin_id)->update(['active' => $request->state]);

        if($request->state == 1 ) return back()->with('success', 'Admin activated successfully !');
        else return back()->with('success', 'Admin suspended successfully !');
    }


    public function get_role_permissions()
    {

        $arr = ['Admins','General Statistics','Financial Statistics','Addresses Observe','Addresses Operate','Categories Observe','Categories Operate'
            ,'Providers General Statistics','Providers Financial Statistics','Providers Subscriptions','Providers Observe','Providers Operate',
            'Companies General Statistics','Companies Financial Statistics','Companies Subscriptions','Companies Observe','Companies Operate','Collaborations Observe','Collaborations Operate',
            'Settings Observe','Settings Operate'];

            $permissions['owner'] = ['General Statistics','Financial Statistics','Addresses Observe','Categories Observe','Providers Observe','Providers General Statistics','Providers Financial Statistics', 'Companies Observe','Companies General Statistics','Companies Financial Statistics','Collaborations Observe','Settings Observe'];
            $permissions['super_admin'] = ['Admins','General Statistics','Financial Statistics','Addresses Observe','Addresses Operate','Categories Observe','Categories Operate','Providers General Statistics','Providers Financial Statistics','Providers Subscriptions','Providers Observe','Providers Operate', 'Companies General Statistics','Companies Financial Statistics','Companies Subscriptions','Companies Observe','Companies Operate','Collaborations Observe','Collaborations Operate', 'Settings Observe','Settings Operate'];
            $permissions['app_manager'] = ['General Statistics','Financial Statistics','Addresses Observe','Addresses Operate','Categories Observe','Categories Operate','Providers General Statistics','Providers Financial Statistics','Providers Subscriptions','Providers Observe','Providers Operate', 'Companies General Statistics','Companies Financial Statistics','Companies Subscriptions','Companies Observe','Companies Operate','Collaborations Observe','Collaborations Operate', 'Settings Observe','Settings Operate'];
            $permissions['users_manager'] = ['General Statistics','Addresses Observe','Categories Observe','Providers Observe','Companies Observe','Collaborations Observe','Settings Observe'];
            $permissions['service_desk'] = ['General Statistics','Addresses Observe','Categories Observe','Providers Observe','Companies Observe','Collaborations Observe','Settings Observe'];
            $permissions['user'] = ['Addresses Observe','Categories Observe','Providers Observe','Companies Observe','Collaborations Observe','Settings Observe'];
    }
}
