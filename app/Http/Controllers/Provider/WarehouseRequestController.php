<?php

namespace App\Http\Controllers\Provider;

use App\Models\WarehouseRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WarehouseRequestController extends Controller
{
    public function index()
    {
        $items = WarehouseRequest::paginate(50);
        return view('provider.warehouse_requests.index', compact('items'));
    }


//    public function destroy(Request $request)
//    {
//        $this->validate($request,
//            [
//                'request_id' => 'required|exists:'.provider()->provider_id.'_warehouse_requests,id'
//            ]
//        );
//
//        WarehouseRequest::where('id', $request->request_id)->delete();
//
//        return back()->with('success', 'Deleted successfully !');
//    }
}
