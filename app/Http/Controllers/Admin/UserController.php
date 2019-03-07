<?php

namespace App\Http\Controllers\Admin;

use App\Models\Address;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index($state)
    {
        if($state == 'active')
        {
            $users = User::where('active' , 1)->paginate(50);
        }
        elseif($state == 'suspended')
        {
            $users = User::where('active' , 0)->paginate(50);
        }

        return view('admin.users.index', compact('users'));
    }


    public function show($id)
    {
        $user = User::find($id);
        return view('admin.users.show', compact('user'));
    }


    public function create()
    {
        $addresses = Address::where('parent_id', NULL)->get();
        $companies = Company::where('active', 1)->get();
        return view('admin.users.single', compact('addresses','companies'));
    }


    public function store(Request $request)
    {
        $this->validate($request,
            [
                'address_id' => 'required|exists:addresses,id',
                'company_id' => 'required',
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required',
                'image' => 'sometimes|image',
                'username' => 'required|unique:users',
                'password' => 'required|confirmed|min:6',
            ],
            [
                'address_id.required' => 'City is required',
                'company_id.required' => 'Company is required',
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.unique' => 'Email already exists',
                'phone.required' => 'Phone is required',
                'image.image' => 'Invalid Image',
                'username.required' => 'Username is required',
                'username.exists' => 'Username already exists',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be 6 digits at minimum',
                'password.confirmed' => 'Password and its confirmation does not match'
            ]
        );

        $user = new User();
            if($request->compan_id != 'no_company')
            {
                $this->validate($request,
                    [
                        'company_id' => 'exists:companies,id'
                    ],
                    [
                        'company_id.exists' => 'Invalid Company'
                    ]
                );
                $user->company_id = $request->company_id;
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->username = $request->username;
            $user->password = Hash::make($request->passowrd);
            if($request->image)
            {
                $image = unique_file($request->image->getClientOriginalName());
                $request->logo->move(base_path().'/public/users/', $image);
                $user->image = $image;
            }
        $user->save();

            return redirect('/admin/users/active')->with('success', 'User added successfully !');
    }
}
