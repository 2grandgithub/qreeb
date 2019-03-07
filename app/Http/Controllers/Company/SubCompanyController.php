<?php

namespace App\Http\Controllers\Company;

use App\Models\SubCompany;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class SubCompanyController extends Controller
{
    public function index($status)
    {
        $subs = SubCompany::where('parent_id', company()->company_id)->where('status', $status)->paginate(50);
        return view('company.sub_companies.index', compact('subs','status'));
    }


    public function search()
    {
        $search = Input::get('search');

        $subs = SubCompany::where('parent_id', company()->company_id)->where(function($q) use($search)
            {
                $q->where('en_name','like','%'.$search.'%');
                $q->orWhere('ar_name','like','%'.$search.'%');
            }
        )->paginate(50);
        return view('company.sub_companies.index', compact('subs','search'));
    }


    public function create()
    {
        return view('company.sub_companies.single');
    }


    public function store(Request $request)
    {
        $this->validate($request,
            [
                'en_name' => 'required|unique:sub_companies,parent_id,'.company()->company_id,
                'ar_name' => 'required|unique:sub_companies,parent_id,'.company()->company_id
            ]
        );


        SubCompany::create
        (
            [
                'parent_id' => company()->company_id,
                'en_name' => $request->en_name,
                'ar_name' => $request->ar_name
            ]
        );

        return redirect('/company/sub_companies/active')->with('success', 'Sub company created successfully !');
    }


    public function users($id, Request $request)
    {
        $request->merge(['sub_company_id' => $id]);

        $this->validate($request,
            [
                'sub_company_id' => 'required|exists:sub_companies,id,parent_id,'.company()->company_id,
            ]
        );

        $users = User::where('company_id', company()->company_id)->where('sub_company_id', $id)->paginate(50);
        return view('company.users.index', compact('users'));
    }


    public function edit($id,Request $request)
    {
        $request->merge(['sub_id' => $id]);

        $this->validate($request,
            [
                'sub_id' => 'required|exists:sub_companies,id,parent_id,'.company()->company_id
            ]
        );

        $sub = SubCompany::find($id);

        return view('company.sub_companies.single', compact('sub'));
    }


    public function update(Request $request)
    {
        $this->validate($request,
            [
                'sub_id' => 'required|exists:sub_companies,id,parent_id,'.company()->company_id,
                'en_name' => 'required',
                'ar_name' => 'required'
            ]
        );

        $en_check = SubCompany::where('en_name', $request->en_name)->where('parent_id', company()->company_id)->where('id','!=',$request->sub_id)->first();
        $ar_check = SubCompany::where('ar_name', $request->en_name)->where('parent_id', company()->company_id)->where('id','!=',$request->sub_id)->first();

        if($en_check) return back()->with('error', 'English name already exists,please try another one');
        if($ar_check) return back()->with('error', 'Arabic name already exists,please try another one');

        SubCompany::where('parent_id', company()->company_id)->where('id', $request->sub_id)->update
        (
            [
                'en_name' => $request->en_name,
                'ar_name' => $request->ar_name
            ]
        );

        return redirect('/company/sub_companies/active')->with('success', 'Sub company updated successfully !');
    }


    public function change_status(Request $request)
    {
        $this->validate($request,
            [
                'sub_id' => 'required|exists:sub_companies,id,parent_id,'.company()->company_id
            ]
        );

        $sub = SubCompany::where('id', $request->sub_id)->first();
            if($sub->status == 'active') $sub->status = 'suspended';
            else $sub->status = 'active';
        $sub->save();

        if($sub->status == 'active') $status = 1;
        else $status = 0;

        User::where('sub_company_id', $sub->id)->update(['active' => $status]);

        return back()->with('success', 'Sub company status updated successfully');
    }



//    public function destroy(Request $request)
//    {
//        $this->validate($request,
//            [
//                'sub_company_id' => 'required|exists:sub_companies,id,parent_id,'.company()->company_id,
//            ]
//        );
//
//
//        SubCompany::where('id', $request->sub_company_id)->delete();
//
//        return redirect('/company/sub_companies/active')->with('success', 'Sub company deleted successfully !');
//    }
}
