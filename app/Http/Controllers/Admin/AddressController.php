<?php

namespace App\Http\Controllers\Admin;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AddressController extends Controller
{
    public function index($parent)
    {
        if($parent == 'all')
        {
            $addresses = Address::where('parent_id', NULL)->paginate(50);
        }
        elseif($parent != 'search')
        {
            $addresses = Address::where('parent_id', $parent)->paginate(50);
        }

        return view('admin.addresses.index', compact('addresses','parent'));
    }

    public function search()
    {
        $search = Input::get('search');
        $addresses = Address::where('en_name','like','%'.$search.'%')->orWhere('ar_name','like','%'.$search.'%')->paginate(50);

        return view('admin.addresses.index', compact('addresses','search'));
    }



    public function country_create()
    {
        return view('admin.addresses.single');
    }


    public function city_create()
    {
        $countries = Address::where('parent_id', NULL)->get();
        return view('admin.addresses.single', compact('countries'));
    }


    public function store(Request $request)
    {
        $this->validate($request,
            [
                'parent_id' => 'sometimes|exists:addresses,id',
                'ar_name' => 'required|unique:addresses',
                'en_name' => 'required|unique:addresses',
            ],
            [
                'parent_id.exists' => 'Please choose a country .',
                'ar_name.required' => 'Please enter an arabic name .',
                'ar_name.unique' => 'Arabic name already exists,please choose another one .',
                'en_name.required' => 'Please enter an english name .',
                'en_name.unique' => 'English name already exists,please choose another one .',
            ]
        );

        $address = Address::create($request->all());

        if($address->parent_id == NULL)
        {
            return redirect('/admin/addresses/all')->with('success', 'Country added auccessfully !');
        }
        else
        {
            return redirect('/admin/addresses/'.$address->parent_id)->with('success', 'City added auccessfully !');
        }
    }


    public function edit($id)
    {
        $address = Address::find($id);

        if($address->parent_id != NULL)
        {
            $countries = Address::where('parent_id', NULL)->get();
            return view('admin.addresses.single', compact('address','countries'));
        }
        else
        {
            return view('admin.addresses.single', compact('address'));
        }
    }


    public function update(Request $request)
    {
        $this->validate($request,
            [
                'address_id' => 'required|exists:addresses,id',
                'parent_id' => 'sometimes|exists:addresses,id',
                'ar_name' => 'required|unique:addresses,ar_name,'.$request->address_id,
                'en_name' => 'required|unique:addresses,en_name,'.$request->address_id,
            ],
            [
                'parent_id.exists' => 'Please choose a country .',
                'ar_name.required' => 'Please enter an arabic name .',
                'ar_name.unique' => 'Arabic name already exists,please choose another one .',
                'en_name.required' => 'Please enter an english name .',
                'en_name.unique' => 'English name already exists,please choose another one .',
            ]
        );

        $address = Address::find($request->address_id);
            if($request->parent_id != NULL)
            {
                $address->parent_id = $request->parent_id;
            }
            $address->en_name = $request->en_name;
            $address->ar_name = $request->ar_name;
        $address->save();

        if($address->parent_id == NULL)
        {
            return redirect('/admin/addresses/all')->with('success', 'Country updated successfully !');
        }
        else
        {
            return redirect('/admin/addresses/'.$address->parent_id)->with('success', 'City updated successfully !');
        }
    }


    public function destroy(Request $request)
    {
        $this->validate($request,
            [
                'address_id' => 'required|exists:addresses,id',
            ]
        );

        Address::where('id', $request->address_id)->delete();

        return back()->with('success','Address deleted successfully !');
    }
}
