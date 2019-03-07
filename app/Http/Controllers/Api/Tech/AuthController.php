<?php

namespace App\Http\Controllers\Api\Tech;

use App\Models\Code;
use App\Models\Order;
use App\Models\Technician;
use App\Models\TechToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required',
                'password' => 'required',
                'token' => 'required'
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }


        $tech = Technician::where('email', $request->email)->select('id','jwt','active','email','password','busy','lat','lng')->first();



        if($tech)
        {
            if($tech->active == 0) return response()->json(msg($request,not_active(),'not_active'));

            $check = Hash::check($request->password,$tech->password);

            if($check)
            {
                TechToken::updateOrcreate
                (
                    [
                        'tech_id' => $tech->id
                    ],
                    [
                        'token' => $request->token
                    ]
                );

                $tech['lat'] = isset($tech->lat) ? $tech->lat : '';
                $tech['lng'] = isset($tech->lng) ? $tech->lng : '';

                unset($tech->email,$tech->password);
                return response()->json(['status' => 'success', 'msg' => 'logged_in', 'data' => $tech]);
            }
            else
            {
                return response()->json(msg($request,failed(),'invalid_password'));
            }

        }
        else
        {
            return response()->json(msg($request,failed(),'invalid_email'));
        }
    }


    public function splash(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,'.$request->tech_id,
                'token' => 'required',
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }


        TechToken::updateOrcreate
        (
            [
                'tech_id' => $request->tech_id,
            ],
            [
                'token' => $request->token
            ]
        );

        return response()->json(['status' => 'success', 'msg' => 'updated']);
    }


    public function code_send(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'type' => 'required|in:activation,reset',
                'email' => 'required',
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $check_email = Technician::where('email',$request->email)->first();
        if(!$check_email) return response()->json(msg($request,failed(),'invalid_email'));

        Code::updateOrcreate
        (
            [
                'type' => $request->type,
                'role' => 'tech',
                'email' => $request->email
            ],
            [
                'code' => rand(1000,9000),
                'expire_at' => Carbon::now()->addHour()->toDateTimeString()
            ]
        );

        return response()->json(msg($request,success(),'code_sent'));
    }


    public function code_check(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'type' => 'required|in:activation,reset',
                'code' => 'required',
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $check = Code::where('code', $request->code)->where('type', $request->type)->where('role', 'tech')->select('email','code')->first();

        if($check)
        {
            if($request->type == 'reset')
            {
                $data = Technician::where('email',$check->email)->select('id','jwt')->first();
            }
            else
            {
                return 'not yet';
            }

            return response()->json(['status' => 'success', 'msg' => 'code matched', 'data' => $data]);
        }
        else
        {
            return response()->json(msg($request,failed(),'invalid_code'));
        }
    }


    public function password_change(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,'.$request->tech_id,
                'password' => 'required',
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $tech = Technician::where('id', $request->tech_id)->first();
            $tech->password = Hash::make($request->password);
        $tech->save();

        return response()->json(msg($request,success(),'password_changed'));
    }


    public function profile(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,'.$request->tech_id,
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }


        $tech = Technician::where('id', $request->tech_id)->select('ar_name','en_name','email','phone','busy')->first();
        $tech['phone'] = isset($tech->phone) ? $tech->phone : '';

        return response()->json($tech);
    }


    public function profile_update(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,'.$request->tech_id,
                'ar_name' => 'required',
                'en_name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'password' => 'sometimes'
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $email_check = Technician::where('id','!=', $request->tech_id)->where('email', $request->email)->first();
        if($email_check) return response()->json(msg($request,failed(),'email_exist'));

        $phone_check = Technician::where('id','!=', $request->tech_id)->where('phone', $request->phone)->first();
        if($phone_check) return response()->json(msg($request,failed(),'phone_exist'));

        $tech = Technician::where('id', $request->tech_id)->first();
            $tech->ar_name = $request->ar_name;
            $tech->en_name = $request->en_name;
            $tech->email = $request->email;
            $tech->phone = $request->phone;
            if($request->password) $tech->password = Hash::make($request->password);
        $tech->save();

        return response()->json(msg($request,success(),'updated'));
    }


    public function set_location(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,'.$request->tech_id,
                'lat' => 'required',
                'lng' => 'required',
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $tech = Technician::where('id', $request->tech_id)->first();
            $tech->lat = $request->lat;
            $tech->lng = $request->lng;
        $tech->save();

        return response()->json(msg($request,success(),'updated'));
    }


    public function status_switch(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'tech_id' => 'required|exists:technicians,id',
                'jwt' => 'required|exists:technicians,jwt,id,'.$request->tech_id,
                'status' => 'required|in:0,1'
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $orders = Order::where('tech_id', $request->tech_id)->where('completed', 0)->count();

        if($orders > 0)
        {
            return response()->json(msg($request,error(),'orders_not_completed'));
        }

        $tech = Technician::where('id', $request->tech_id)->first();
            $tech->busy = $request->status;
        $tech->save();

        return response()->json(msg($request,success(),'updated'));
    }

}
