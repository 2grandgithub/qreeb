<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsProvider
{


    public function handle($request, Closure $next)
    {
            if (Auth::guard('provider')->user()   && Auth::guard('provider')->user()->active == 1)
            {
                return $next($request);
            }
            else
            {
//                $request->session()->flush();
//                $request->session()->regenerate();
//
//                Auth::logout();

                return redirect('/provider/login');
            }
    }


}
