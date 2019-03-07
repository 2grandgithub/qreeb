<?php

namespace App\Http\Controllers\Admin;

use App\Models\Collaboration;
use App\Models\Company;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CollaborationController extends Controller
{
    public function index()
    {
        $colls = Collaboration::get()->groupBy('provider_id');
        return view('admin.collaborations.index',compact('colls'));
    }


    public function search()
    {
        $search = Input::get('search');
        $providers = Provider::where('active', 1)->where(function ($q) use ($search)
            {
                $q->where('en_name','like','%'.$search.'%');
                $q->orWhere('ar_name','like','%'.$search.'%');
                $q->orWhere('email','like','%'.$search.'%');
            }
        )->pluck('id');

        $colls = Collaboration::whereIn('provider_id', $providers)->get()->groupBy('provider_id');

        return view('admin.collaborations.index',compact('colls','search'));
    }


    public function create()
    {
        $providers = Provider::get();
        $companies = Company::get();

        return view('admin.collaborations.single', compact('providers','companies'));
    }


    public function store(Request $request)
    {
        $this->validate($request,
            [
                'provider_id' => 'required|exists:providers,id',
                'companies' => 'required|array',
                'companies.*' => 'exists:companies,id'
            ]
        );

        foreach($request->companies as $company_id)
        {
            Collaboration::updateOrcreate
            (
                [
                    'provider_id' => $request->provider_id,
                    'company_id' => $company_id
                ]
            );
        }

        return redirect('/admin/collaborations')->with('success', 'Collaborations created successfully');
    }


    public function edit($provider_id)
    {
        $provider = Provider::find($provider_id);
        $companies = Company::get();
        $collaboration = Collaboration::where('provider_id', $provider_id)->get();

        return view('admin.collaborations.single', compact('collaboration','provider','companies'));
    }


    public function update(Request $request)
    {
        $this->validate($request,
            [
                'provider_id' => 'required|exists:providers,id',
                'companies' => 'required|array',
                'companies.*' => 'exists:companies,id'
            ]
        );


        foreach($request->companies as $company_id)
        {

            Collaboration::updateOrcreate
            (
                [
                    'provider_id' => $request->provider_id,
                    'company_id' => $company_id
                ]
            );
        }

        Collaboration::where('provider_id', $request->provider_id)->whereNotIn('company_id', $request->companies)->delete();

        return redirect('/admin/collaborations')->with('success', 'Collaborations updated successfully');
    }


    public function destroy(Request $request)
    {
        $this->validate($request,
            [
                'provider_id' => 'required|exists:providers,id',
            ]
        );

        Collaboration::where('provider_id', $request->provider_id)->delete();

        return back()->with('success', 'Collaboration deleted successfully');
    }
}
