<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\AboutUs;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $about = AboutUs::first();

        return view('admin.settings.abouts.index', compact('about'));
    }


    public function edit()
    {
        $about = AboutUs::first();
        return view('admin.settings.abouts.single', compact('about'));
    }


    public function update(Request $request)
    {
        $this->validate($request,
            [
                'en_text' => 'required',
                'ar_text' => 'required',
            ],
            [
                'en_text.required' => 'English text is required',
                'ar_text.required' => 'Arabic text is required',
            ]
        );

        $about = AboutUs::first();
            $about->en_text = $request->en_text;
            $about->ar_text = $request->ar_text;
        $about->save();

        return redirect('/admin/settings/about')->with('success', 'Updated successfully');
    }


}
