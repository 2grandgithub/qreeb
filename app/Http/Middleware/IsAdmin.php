<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{


    public function handle($request, Closure $next)
    {
            if (Auth::guard('admin')->user()  && Auth::guard('admin')->user()->active == 1)
            {
                return $next($request);
            }
            else
            {
                return redirect('/admin/login');
            }
    }


}
