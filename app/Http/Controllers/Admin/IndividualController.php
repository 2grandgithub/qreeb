<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Technician;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class IndividualController extends Controller
{
    public function index($state)
    {
        if($state == 'active')
        {
            $techs = Technician::where('type', 'individual')->where('active' , 1)->paginate(50);
        }
        elseif($state == 'suspended')
        {
            $techs = Technician::where('type', 'individual')->where('active' , 0)->paginate(50);
        }

        return view('admin.individuals.index', compact('techs'));
    }


    public function create()
    {
        $cats = Category::where('parent_id', NULL)->get();
        return view('admin.individuals.single', compact('cats'));
    }


    public function store(Request $request)
    {
        $this->validate($request,
            [
                'cat_id' => 'required|exists:categories,id',
                'username' => 'required|unique:technicians,username',
                'password' => 'required|min:6|confirmed',
                'ar_name' => 'required',
                'en_name' => 'required',
                'email' => 'required|unique:technicians,email',
                'phone' => 'required|unique:technicians,phone',
                'image' => 'sometimes|image',
            ],
            [
                'cat_id.required' => 'Category is required',
                'username.required' => 'Username is required',
                'username.unique' => 'Username already exists,please choose another one',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be 6 digits at least',
                'password.confirmed' => 'Password and its confirmation does not match',
                'ar_name.required' => 'Arabic Name is required',
                'en_name.required' => 'English Name is required',
                'email.required' => 'Email is required',
                'email.unique' => 'Email already exists,please choose another one',
                'phone.required' => 'Phone is required',
                'phone.unique' => 'Phone already exists,please choose another one',
                'image.image' => 'Image is invalid',
            ]
        );

        $technician = Technician::create(
            [
                'provider_id' => 1,
                'type' => 'individual',
                'cat_id' => $request->cat_id,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'en_name' => $request->en_name,
                'ar_name' => $request->ar_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]
        );

        if($request->image)
        {
            $image = unique_file($request->image->getClientOriginalName());
            $request->image->move(base_path().'/public/individuals/', $image);
            $technician->image = $image;
            $technician->save();
        }


        return redirect('/admin/individuals/active')->with('success', 'Technician added successfully !');
    }


    public function edit($id)
    {
        $technician = Technician::find($id);
        $cats = Category::where('parent_id', NULL)->get();
        return view('admin.individuals.single', compact('technician','cats'));
    }


    public function change_status(Request $request)
    {
        $this->validate($request,
            [
                'tech_id' => 'required|exists:technicians,id',
                'state' => 'required|in:0,1',
            ]
        );

        $technician = Technician::find($request->tech_id);
        $technician->active = $request->state;
        $technician->save();

        if($technician->active == 1)
        {
            return back()->with('success', 'Technician activated successfully !');
        }
        else
        {
            return back()->with('success', 'Technician suspended successfully !');
        }
    }
}
