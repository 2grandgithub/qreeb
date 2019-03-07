<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsCompany
{


    public function handle($request, Closure $next)
    {
            if (Auth::guard('company')->user() && Auth::guard('company')->user()->active == 1)
            {
                return $next($request);
            }
            else
            {
//                $request->session()->flush();
//                $request->session()->regenerate();

//                Auth::logout();

                return redirect('/company/login');
            }
    }


}
