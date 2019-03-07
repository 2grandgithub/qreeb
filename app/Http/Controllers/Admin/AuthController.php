<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login_view()
    {
        if(Auth::guard('admin')->user())
        {
            return redirect('/admin/dashboard');
        }

        return view('admin.login.login');
    }


    public function login(Request $request)
    {
        $this->validate($request,
            [
                'email' => 'required|exists:admins,email,active,1',
                'password' => 'required'
            ],
            [
                'email.exists' => 'Sorry,invalid email!',
            ]
        );

        if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], false))
        {
            return redirect('/admin/dashboard');
        }
        else
        {
            return back()->with('error','Invalid Credentials');
        }
    }


    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}
