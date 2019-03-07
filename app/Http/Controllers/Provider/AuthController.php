<?php

namespace App\Http\Controllers\Provider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login_view()
    {
        if(Auth::guard('provider')->user())
        {
            return redirect('/provider/dashboard');
        }

        return view('provider.login.login');
    }


    public function login(Request $request)
    {
        $this->validate($request,
            [
                'username' => 'required|exists:provider_admins,username,active,1',
                'password' => 'required'
            ],
            [
                'username.required' => 'Username is required',
                'username.exists' => 'Username is invalid',
                'password.required' => 'Password is required'
            ]
        );


       if(Auth::guard('provider')->attempt(['username' => $request->username, 'password' => $request->password], false))
       {
           return redirect('/provider/dashboard');
       }
       else
       {
           return back()->with('error','Invalid Credentials');
       }
    }


    public function logout()
    {
        Auth::guard('provider')->logout();
        return redirect('/provider/login');
    }
}
