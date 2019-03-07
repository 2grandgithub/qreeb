<?php

namespace App\Http\Controllers\Provider;

use App\Models\Category;
use App\Models\Provider;
use App\Models\ProviderCategoryFee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceFeeController extends Controller
{
    public function view()
    {
        $ids = ProviderCategoryFee::where('provider_id', provider()->provider_id)->pluck('cat_id');
        $parents = Category::whereIn('id', $ids)->pluck('parent_id');

        $cats = Category::whereIn('id', $parents)->get();

        return view('provider.services.single', compact('cats'));
    }


    public function update(Request $request)
    {
        foreach($request->fees as $inc => $data)
        {
            $this_request = new Request(array_keys($data));
            $this->validate($this_request,
                [
                    '0' => 'required|exists:categories,id,type,2'
                ]
            );

            foreach($data as $id => $fee)
            {
                ProviderCategoryFee::updateOrcreate
                (
                    [
                        'provider_id' => provider()->provider_id,
                        'cat_id' => $id
                    ],
                    [
                        'fee' => $fee
                    ]
                );
            }
        }

        return back()->with('success', 'Services Fees updated successfully');
    }
}
