<?php

namespace App\Http\Controllers\Api\User;

use App\Models\AboutUs;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CompanySubscription;
use App\Models\Complain;
use App\Models\ComplainTitle;
use App\Models\Provider;
use App\Models\Collaboration;
use App\Models\ProviderSubscription;
use App\Models\UserNot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'jwt' => 'required|exists:users,jwt,id,' . $request->user_id,
                'company_id' => 'required|exists:companies,id|exists:users,company_id,id,' . $request->user_id
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $lang = $request->header('lang');

        $company_providers_ids = Collaboration::where('company_id', $request->company_id)->pluck('provider_id');
        $provider_subs = ProviderSubscription::whereIn('provider_id', $company_providers_ids)->pluck('subs');
        $companies_subs = CompanySubscription::where('company_id', $request->company_id)->pluck('subs');

        $subs_provider_array = [];
        $subs_company_array = [];

        foreach($provider_subs as $sub)
        {
            $subs_provider_array = array_merge(unserialize($sub),$subs_provider_array);
        }

        foreach($companies_subs as $sub)
        {
            $subs_company_array = array_merge(unserialize($sub),$subs_company_array);
        }

        $subs_array = array_intersect($subs_provider_array,$subs_company_array);

        $sub_categories = Category::whereIn('id', $subs_array)->pluck('parent_id');
        $categories = Category::whereIn('id', $sub_categories)->select('id',$lang.'_name as name','image')->get();

        foreach ($categories as $category)
        {
            $category['image'] = 'http://'.$_SERVER['SERVER_NAME'].'/public/categories/'.$category->image;
        }

        return response()->json($categories);
    }


    public function sub_cats(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'parent_id' => 'required|exists:categories,id'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $lang = $request->header('lang');

        $sub_cats = Category::where('parent_id', $request->parent_id)->select('id', $lang . '_name as name', 'image')->get();

        foreach ($sub_cats as $category)
        {
            $category['image'] = 'http://'.$_SERVER['SERVER_NAME'].'/public/categories/'.$category->image;
        }
        return response()->json($sub_cats);
    }


    public function about_us($lang)
    {
        $text = AboutUs::select($lang.'_text as text')->first();
        return response()->json($text);
    }


    public function complain_view($lang)
    {
        $titles = ComplainTitle::select('id',$lang.'_title as title')->get();
        return response()->json($titles);
    }


    public function complain(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title_id' => 'required|exists:complain_titles,id',
                'desc' => 'required',
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        Complain::create
        (
            [
                'title_id' => $request->title_id,
                'desc' => $request->desc
            ]
        );

        return response()->json(msg($request,success(),'thanks'));
    }


    public function notifications(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'jwt' => 'required|exists:users,jwt,id,'.$request->user_id,
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        $lang = $request->header('lang');
        $nots = UserNot::where('user_id', $request->user_id)->select('id','seen','type','order_id',$lang.'_text as text','created_at')->latest()->get();

        return response()->json($nots);
    }


    public function seen(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'jwt' => 'required|exists:users,jwt,id,'.$request->user_id,
                'not_id' => 'required|exists:user_nots,id,user_id,'.$request->user_id,
            ]
        );

        if($validator->fails())
        {
            return response()->json(['status' => 'failed', 'msg' => $validator->messages()]);
        }

        UserNot::where('id', $request->not_id)->update(['seen' => 1]);

        return response()->json(['status' => 'success', 'msg' => 'notification is seen successfully']);
    }
}
