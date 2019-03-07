<?php

namespace App\Http\Controllers\Provider;

use App\Models\Rotation;
use App\Models\Technician;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RotationController extends Controller
{
    public function index()
    {
        $rotations = Rotation::where('provider_id', provider()->provider_id)->get();
        return view('provider.rotations.index', compact('rotations'));
    }


    public function create()
    {
        return view('provider.rotations.single');
    }


    public function store(Request $request)
    {
        $this->validate($request,
            [
                'en_name' => 'required|unique:rotations,en_name,'.provider()->provider_id,
                'ar_name' => 'required|unique:rotations,en_name,'.provider()->provider_id,
                'from' => 'required|date_format:H:i',
                'to' => 'required|date_format:H:i',
            ],
            [
                'en_name.required' => 'Rotation english name is required',
                'en_name.unique' => 'Rotation english name already exists',
                'ar_name.required' => 'Rotation arabic name is required',
                'ar_name.unique' => 'Rotation arabic name already exists',
                'from.required' => 'The rotation start is required',
                'from.date_format' => 'The rotation start is in invalid format',
                'to.required' => 'The rotation end is required',
                'to.date_format' => 'The rotation end is in invalid format',
            ]
        );

        Rotation::create
        (
            [
                'provider_id' => provider()->provider_id,
                'en_name' => $request->en_name,
                'ar_name' => $request->ar_name,
                'from' => $request->from,
                'to' => $request->to,
            ]
        );

        return redirect('/provider/rotations/index')->with('success','Rotation created successfully !');
    }


    public function edit($id, Request $request)
    {
        $request->merge(['rotation_id' => $id]);

        $this->validate($request,
            [
                'rotation_id' => 'required|exists:rotations,id,provider_id,'.provider()->provider_id
            ]
        );

        $rotation = Rotation::find($request->rotation_id);

        return view('provider.rotations.single', compact('rotation'));
    }


    public function update(Request $request)
    {
        $this->validate($request,
            [
                'rotation_id' => 'required|exists:rotations,id,provider_id,'.provider()->provider_id,
                'en_name' => 'required|unique:rotations,en_name,'.$request->rotation_id.',id',
                'ar_name' => 'required|unique:rotations,ar_name,'.$request->rotation_id.',id',
                'from' => 'required|date_format:H:i',
                'to' => 'required|date_format:H:i',
            ],
            [
                'rotation_id.required' => 'Rotation is required',
                'rotation_id.exists' => 'Rotation is invalid',
                'en_name.required' => 'Rotation english name is required',
                'en_name.unique' => 'Rotation english name already exists',
                'ar_name.required' => 'Rotation arabic name is required',
                'ar_name.unique' => 'Rotation arabic name already exists',
                'from.required' => 'The rotation start is required',
                'from.date_format' => 'The rotation start is in invalid format',
                'to.required' => 'The rotation start is required',
                'to.date_format' => 'The rotation start is in invalid format',
            ]
        );

        $rotation = Rotation::find($request->rotation_id);
            $rotation->en_name = $request->en_name;
            $rotation->ar_name = $request->ar_name;
            $rotation->from = $request->from;
            $rotation->to = $request->to;
        $rotation->save();

        return redirect('/provider/rotations/index')->with('success', 'Rotation updated successfully !');
    }


    public function destroy(Request $request)
    {
        $this->validate($request,
            [
                'rotation_id' => 'required|exists:rotations,id,provider_id,'.provider()->provider_id,
                'alt_rotation_id' => 'required|exists:rotations,id,provider_id,'.provider()->provider_id
            ],
            [
                'rotation_id.required' => 'Rotation is required',
                'rotation_id.exists' => 'Rotation is invalid',
                'alt_rotations_id' => 'Alternative rotation is required',
                'alt_rotation_id.exists' => 'Alternative rotation is invalid'
            ]
        );

        Technician::where('provider_id', provider()->provider_id)->where('rotation_id', $request->rotation_id)->update(['rotation_id' => $request->alt_rotation_id]);

        Rotation::where('id', $request->rotation_id)->delete();

        return back()->with('success', 'Rotation deleted and technicians transferred successfully !');
    }


    public function assign(Request $request)
    {
        $this->validate($request,
            [
                'rotation_id' => 'required|exists:rotations,id,provider_id,'.provider()->provider_id,
                'tech_id' => 'required|exists:technicians,id,provider_id,'.provider()->provider_id
            ],
            [
                'rotation_id.required' => 'Rotation is required',
                'rotation_id.exists' => 'Rotation is invalid',
                'tech_id' => 'Technician is required',
                'tech_id.exists' => 'Technician is invalid'
            ]
        );

        Technician::where('provider_id', $request->provider_id)->where('id', $request->tech_id)->update(['rotation_id' => $request->rotation_id]);


        return back()->with('success', 'Technician has been assigned to the rotation successfully !');
    }
}
