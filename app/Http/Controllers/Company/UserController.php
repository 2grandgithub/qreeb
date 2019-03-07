<?php

namespace App\Http\Controllers\Company;

use App\Imports\UsersImport;
use App\Models\Category;
use App\Models\CompanySubscription;
use App\Models\Order;
use App\Models\SubCompany;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class UserController extends Controller
{
    public function index($state)
    {
        if($state == 'active')
        {
            $users = User::where('company_id', company()->company_id)->where('active', 1)->paginate(50);
        }
        elseif('suspended')
        {
            $users = User::where('company_id', company()->company_id)->where('active', 0)->paginate(50);
        }

        return view('company.users.index', compact('users'));
    }


    public function search()
    {
        $search = Input::get('search');
        $users = User::where('company_id', company()->company_id)->where(function($q) use($search)
            {
                $q->where('en_name','like','%'.$search.'%');
                $q->orWhere('ar_name','like','%'.$search.'%');
                $q->orWhere('email','like','%'.$search.'%');
                $q->orWhere('phone','like','%'.$search.'%');
                $q->orWhere('badge_id','like','%'.$search.'%');
            }
        )->paginate(50);

        return view('company.users.index', compact('users','search'));
    }



    public function create()
    {
        $subs = SubCompany::where('parent_id', company()->company_id)->get();
        return view('company.users.single', compact('subs'));
    }


    public function store(Request $request)
    {
        $this->validate($request,
            [
                'sub_company_id' => 'required|exists:sub_companies,id,parent_id,'.company()->company_id,
                'badge_id' => 'required',
                'en_name' => 'required',
                'ar_name' => 'required',
                'email' => 'required|unique:users,email,'.$request->user_id,
                'phone' => 'required|unique:users,phone,'.$request->user_id,
                'password' => 'required|confirmed',
                'image' => 'sometimes|image',
                'plot_no' => 'required',
                'block_no' => 'required',
                'building_no' => 'required',
                'apartment_no' => 'required',
                'house_type' => 'required'
            ]
        );

        $badge_check = User::where('badge_id', $request->badge_id)->where('company_id',company()->company_id)->first();

        if($badge_check)
        {
            return back()->with('error', 'Sorry,this Badge ID already exists');
        }

        $user = new User();
            $user->jwt = str_random(25);
            $user->company_id = company()->company_id;
            $user->sub_company_id = $request->sub_company_id;
            $user->badge_id = $request->badge_id;
            $user->en_name = $request->en_name;
            $user->ar_name = $request->ar_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->plot_no = $request->plot_no;
            $user->block_no = $request->block_no;
            $user->building_no = $request->building_no;
            $user->apartment_no = $request->apartment_no;
            $user->house_type = $request->house_type;
            if($request->password) $user->password = Hash::make($request->password);
            if($request->image)
            {
                $name = unique_file($request->image->getClientOriginalName());
                $request->image->move(base_path().'/public/companies/users/',$name);
                $user->image = $name;
            }
        $user->save();

        return redirect('/company/users/active')->with('success', 'User created successfully');
    }


    public function show($id, Request $request)
    {
        $request->merge(['user_id' => $id]);
        $this->validate($request,
            [
                'user_id' => 'required|exists:users,id,company_id,'.company()->company_id
            ]
        );

        $user = User::find($id);

        return view('company.users.show', compact('user'));
    }


    public function edit($id, Request $request)
    {
        $request->merge(['user_id' => $id]);
        $this->validate($request,
            [
                'user_id' => 'required|exists:users,id,company_id,'.company()->company_id
            ]
        );

        $subs = SubCompany::where('parent_id', company()->company_id)->get();
        $user = User::find($id);
        return view('company.users.single', compact('user','subs'));
    }


    public function update(Request $request)
    {
        $this->validate($request,
            [
                'sub_company_id' => 'required|exists:sub_companies,id,parent_id,'.company()->company_id,
                'user_id' => 'required|exists:users,id,company_id,'.company()->company_id,
                'badge_id' => 'required',
                'en_name' => 'required',
                'ar_name' => 'required',
                'email' => 'required|unique:users,email,'.$request->user_id,
                'phone' => 'required|unique:users,phone,'.$request->user_id,
                'password' => 'sometimes|confirmed',
                'image' => 'sometimes|image',
                'plot_no' => 'required',
                'block_no' => 'required',
                'building_no' => 'required',
                'apartment_no' => 'required',
                'house_type' => 'required'
            ]
        );

        $badge_check = User::where('badge_id', $request->badge_id)->where('company_id',company()->company_id)->where('id','!=',$request->user_id)->first();

        if($badge_check)
        {
            return back()->with('error', 'Sorry,this Badge ID already exists');
        }

        $user = User::where('id', $request->user_id)->first();
            $user->badge_id = $request->badge_id;
            $user->en_name = $request->en_name;
            $user->ar_name = $request->ar_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->plot_no = $request->plot_no;
            $user->block_no = $request->block_no;
            $user->building_no = $request->building_no;
            $user->apartment_no = $request->apartment_no;
            $user->house_type = $request->house_type;
            if($request->password) $user->password = Hash::make($request->password);
            if($request->image)
            {
                if($user->image != 'default_user.png') unlink(base_path().'/public/companies/users/'.$user->image);
                $name = unique_file($request->image->getClientOriginalName());
                $request->image->move(base_path().'/public/companies/users/',$name);
                $user->image = $name;
            }
        $user->save();

        if($user->active == 1) return redirect('/company/users/active')->with('success', 'User updated successfully');
        else return redirect('/company/users/suspended')->with('success', 'User updated successfully');
    }


    public function change_state(Request $request)
    {
        $this->validate($request,
            [
                'user_id' => 'required|exists:users,id,company_id,'.company()->company_id
            ]
        );

        $user = User::where('id', $request->user_id)->first();
            if($user->active == 1) $user->active = 0;
            else $user->active = 1;
        $user->save();

        return back()->with('success', 'User deleted successfully');
    }


    public function change_password(Request $request)
    {
        $this->validate($request,
            [
                'user_id' => 'required|exists:users,id,company_id,'.company()->company_id,
                'password' => 'required|confirmed'
            ]
        );

        $user = User::where('id', $request->user_id)->first();
            $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'User password updated successfully');
    }


//    public function destroy(Request $request)
//    {
//        $this->validate($request,
//            [
//                'user_id' => 'required|exists:users,id,company_id,'.company()->company_id
//            ]
//        );
//
//        $user = User::where('id', $request->user_id)->first();
//        if($user->image != 'default_user.png') unlink(base_path().'/public/companies/users/'.$user->image);
//        $user->delete();
//
//        return back()->with('success', 'User deleted successfully');
//    }


    public function excel_view()
    {
        return view('company.users.upload');
    }


    public function excel_upload(Request $request)
    {
        $this->validate($request,
            [
                'file' => 'required|file'
            ],
            [
                'file.required' => 'Excel file is required',
                'file.file' => 'Invalid Excel file'
            ]
        );

        $array = Excel::toArray(new UsersImport(),$request->file('file'));

        foreach($array[0] as $data)
        {
            if(count(array_filter($data)) > 0)
            {
                try
                {
                    $request->merge(['sub_company_id' => $data[0],'badge_id' => $data[1],'status' => $data[2], 'en_name' => $data[3],
                        'ar_name' => $data[4],'email' => $data[5],'phone' => $data[6], 'password' => $data[7],
                        'camp' => $data[8],'street' => $data[9],'plot_no' => $data[10], 'block_no' => $data[11],
                        'building_no' => $data[12],'apartment_no' => $data[13],'house_type' => $data[14]]);
                }
                catch (\Exception $e)
                {
                    return back()->with('error','Missing Column | '.$e->getMessage().',Offsets start from 0');
                }


                $this->validate($request,
                    [
                        'sub_company_id' => 'required|exists:sub_companies,id,parent_id,'.company()->company_id,
                        'badge_id' => 'required',
                        'status' => 'required|in:active,suspended',
                        'en_name' => 'required',
                        'ar_name' => 'required',
                        'email' => 'required|email|unique:users,email,'.$request->badge_id.',badge_id',
                        'phone' => 'required|unique:users,phone,'.$request->badge_id.',badge_id',
                        'password' => 'required',
                        'camp' => 'required',
                        'street' => 'required',
                        'plot_no' => 'required',
                        'block_no' => 'required',
                        'building_no' => 'required',
                        'apartment_no' => 'required',
                        'house_type' => 'required',
                    ],
                    [
                        'sub_company_id.required' => 'Missing data in Sub Company ID column.',
                        'sub_company_id.exists' => 'Invalid data in Sub Company ID column.',
                        'badge_id.required' => 'Missing data in Badge ID column.',
                        'status.required' => 'Missing data in Status column.',
                        'status.in' => 'Invalid data in Status column,which is '.$request->active.',only active & suspended are allowed.',
                        'en_name.required' => 'Missing data in English Name column.',
                        'ar_name.required' => 'Missing data in Arabic Name column.',
                        'email.required' => 'Missing data in Email column.',
                        'email.email' => 'Invalid data in Email column which is '.$request->email.'.',
                        'email.unique' => 'Email already exists,which is '.$request->email.'.',
                        'phone.required' => 'Missing data in Phone column.',
                        'phone.unique' => 'Phone already exists,which is '.$request->phone.'.',
                        'password.required' => 'Missing data in password column.',
                        'camp.required' => 'Missing data in Camp column.',
                        'street.required' => 'Missing data in Street column.',
                        'plot_no.required' => 'Missing data in Plot No. column.',
                        'block_no.required' => 'Missing data in Block No. column.',
                        'building_no.required' => 'Missing data in Building No. column.',
                        'apartment_no.required' => 'Missing data in Apartment No. column.',
                        'house_type.required' => 'Missing data in House Type column.',
                    ]
                );

                if($request->status == 'active' ) $status = 1;
                else $status = 0;

                User::updateOrCreate
                (
                    [
                        'company_id' => company()->company_id,
                        'sub_company_id' => $data[0],
                        'badge_id' => $data[1],
                    ],
                    [
                        'jwt' => str_random(25),
                        'active' => $status,
                        'en_name' => $data[3],
                        'ar_name' => $data[4],
                        'email' => $data[5],
                        'phone' => $data[6],
                        'password' => Hash::make($data[7]),
                        'camp' => $data[8],
                        'street' => $data[9],
                        'plot_no' => $data[10],
                        'block_no' => $data[11],
                        'building_no' => $data[12],
                        'apartment_no' => $data[13],
                        'house_type' => $data[14],
                    ]
                );
            }
        }
        
        return redirect('/company/users/active')->with('success', 'Users uploaded successfully');
    }


    public function images_view()
    {
        return view('company.users.upload_images');
    }


    public function images_upload(Request $request)
    {
        $this->validate($request,
            [
                'file' => 'required|mimes:zip'
            ],
            [
                'file.required' => 'Compressed file is required',
                'file.mimes' => 'Compressed file must be a .zip',
            ]
        );

        try
        {
            $zip = new ZipArchive();
            $tmp_dir = base_path('/public/companies/'.company()->company_id.'_tmp_images');

            try
            {
                $zip->open($request->file);
                $zip->extractTo($tmp_dir);

                $images = array_diff(scandir($tmp_dir),['.','..']);

                foreach($images as $image)
                {
                    $explode = explode('.',$image);

                    $user = User::where('company_id', company()->company_id)->where('badge_id', $explode[0])->first();

                    if($user)
                    {
                        $name = unique_file($image);

                        File::copy($tmp_dir.'/'.$image,base_path().'/public/companies/users/'.$name);
                        File::delete($tmp_dir.'/'.$image);

                        if($user->image != 'default_user.png') $old_image = $user->image;
                        $user->image = $name;
                        $user->save();

                        if(isset($old_image)) unlink(base_path().'/public/companies/users/'.$old_image);
                    }
                    else
                    {
                        return back()->with('error', 'Invalid Badge ID for the image named '. $image);
                    }
                }

                rmdir($tmp_dir);
            }
            catch(\Exception $e)
            {
                rmdir($tmp_dir);
                return back()->with('error', 'Error has occurred while unzipping the file | '. $e);
            }

        }
        catch (\Exception $e)
        {
            return back()->with('error', 'Error has occurred while uploading the zip file| '.$e->getMessage());
        }

        return redirect('/company/users/active')->with('success', 'Images uploaded & set successfully');
    }


    public function orders_request($user_id, Request $request)
    {
        $request->merge(['user_id' => $user_id]);
        $this->validate($request,
            [
                'user_id' => 'required|exists:users,id,company_id,'.company()->company_id
            ]
        );

        $users = User::where('company_id', company()->company_id)->select('id','en_name')->get();
        return view('company.users.orders_info_request', compact('user_id','users'));
    }


    public function orders_show(Request $request)
    {
        $this->validate($request,
            [
                'user_id' => 'required|exists:users,id,company_id,'.company()->company_id,
                'from' => 'required|date',
                'to' => 'required|date'
            ],
            [
                'user_id.required' => 'Please choose a user',
                'user_id.exists' => 'Invalid User',
                'from.required' => 'Please choose a date to start from',
                'from.date' => 'Please choose a valid date to start from',
                'to.required' => 'Please choose a date to end with',
                'to.date' => 'Please choose a valid date to end with',
            ]
        );

        $orders = Order::where('company_id', company()->company_id)->where('user_id', $request->user_id)->where('created_at','>=',$request->from)->where('created_at','<=',$request->to)->get();
        $orders[] = collect(['total' => $orders->sum('order_total')]);

        $user = User::where('id', $request->user_id)->select('id','en_name as name')->first();
        $from = $request->from;
        $to = $request->to;

        return view('company.users.orders_info_show', compact('orders','user','from','to'));
    }


    public function orders_export(Request $request)
    {
        $this->validate($request,
            [
                'user_id' => 'required|exists:users,id,company_id,'.company()->company_id,
                'from' => 'required|date',
                'to' => 'required|date'
            ]
        );


        $orders = new Collection();
        $get_orders = Order::where('company_id', company()->company_id)->where('user_id', $request->user_id)->where('created_at','>=',$request->from)->where('created_at','<=',$request->to)->get();

        foreach($get_orders as $order)
        {
            if($order->type == 'urgent') $type = 'Urgent';
            elseif($order->type == 'scheduled') $type = 'Scheduled';
            else $type = 'Re-Scheduled';

            $collect['Category'] = $order->category->parent->en_name . ' - ' . $order->category->en_name;
            $collect['Date'] = $order->created_at->toDateTimeString();
            $collect['Type'] = $type;
            $collect['Cost'] = $order->order_total;

            $orders = $orders->push($collect);
        }


        $orders[] = collect(['Category' => '-','Date' => '-','Type' => '-','Cost' => '-','Total' => $orders->sum('Cost')]);

        $orders = $orders->toArray();
        $user = User::where('id', $request->user_id)->select('en_name')->first();
        $from = $request->from;
        $to = $request->to;
        $p_name = str_replace(' ','-',$user->en_name);

        $filename = 'qareeb_user_'.$p_name.'_'.$from.'_'.$to.'_orders_invoice.xls';


        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");

        $heads = false;
        foreach($orders as $order)
        {
            if($heads == false)
            {
                echo implode("\t", array_keys($order)) . "\n";
                $heads = true;
            }
            {
                echo implode("\t", array_values($order)) . "\n";
            }
        }

        die();
    }


    public function order_create($user_id, Request $request)
    {
        $request->merge(['user_id' => $user_id]);

        $this->validate($request,
            [
                'user_id' => 'required|exists:users,id,company_id,'.company()->company_id
            ]
        );

        $types['urgent'] = 'Urgent';
        $types['scheduled'] = 'Scheduled';

        $subs = CompanySubscription::where('company_id', company()->company_id)->first()->subs;
        $cat_ids = Category::whereIn('id', unserialize($subs))->pluck('parent_id');
        $cats = Category::whereIn('id', $cat_ids)->select('id','en_name')->get();

        $user = User::where('company_id', company()->company_id)->where('id', $request->user_id)->select('id','en_name')->first();

        return view('company.users.order_single', compact('user_id','types','cats','user'));
    }


    public function order_store(Request $request)
    {
        $this->validate($request,
            [
                'mso' => 'sometimes|nullable|unique:orders,smo',
                'user_id' => 'exists:users,id,active,1,company_id,'.company()->company_id,
                'type' => 'required|in:urgent,scheduled',
                'cat_id' => 'required|exists:categories,id,type,2',
                'date' => 'sometimes|date',
                'time' => 'sometimes|date_format:H:i:s'
            ],
            [
                'mso.unique' => 'MSO is already taken',
                'user_id.required' => 'User is required',
                'user_id.exists' => 'User is invalid',
                'type.required' => 'Type is required',
                'type.in' => 'Type is invalid',
                'cat_id.required' => 'Category is required',
                'cat_id.exists' => 'Category is invalid',
                'date.date' => 'Date is invalid',
                'time.date_format' => 'Time is invalid',
            ]
        );

        $order = new Order();
            if($request->mso) $order->smo = $request->mso;
            $order->user_id = $request->user_id;
            $order->company_id = company()->company_id;
            $order->type = 'scheduled';
            $order->scheduled_at = $request->date .' '. $request->time;
            $order->code = rand(1000, 9999);
            $order->cat_id = $request->cat_id;
        $order->save();

        return redirect('/company/users/active')->with('success', 'Order has been scheduled successfully !');
    }
}
