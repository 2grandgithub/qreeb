<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

function unique_file($fileName)
{
    $fileName = str_replace(' ','-',$fileName);
    return time() . uniqid().'-'.$fileName;
}


function admin()
{
    return Auth::guard('admin')->user();
}



function provider()
{
    return Auth::guard('provider')->user();
}


function company()
{
    return Auth::guard('company')->user();
}



function success()
{
    return 'success';
}


function not_active()
{
    return 'not_active';
}


function error()
{
    return 'error';
}


function failed()
{
    return 'failed';
}



function msg($request,$status,$key)
{
    $msg['status'] = $status;
    $msg['msg'] = Config::get('response.'.$key.'.'.$request->header('lang'));

    return $msg;
}


function get_auth_guard()
{
    $path = request()->route()->getPrefix();

    if($path == '/admin' xor $path == 'admin/settings') return Auth::guard('admin')->user();
    elseif($path == '/provider') return Auth::guard('provider')->user();
    elseif($path == '/company') return Auth::guard('company')->user();
}


