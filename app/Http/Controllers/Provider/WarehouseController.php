<?php

namespace App\Http\Controllers\Provider;

use App\Imports\WarehousesImport;
use App\Models\Category;
use App\Models\ProviderCategoryFee;
use App\Models\ProviderSubscription;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class WarehouseController extends Controller
{
    public function index($parent)
    {
        if($parent == 'all') $categories = Category::where('parent_id', NULL)->paginate(50);
        else $categories = Category::where('parent_id', $parent)->paginate(50);

        return view('provider.warehouse.cats', compact('parent','categories'));
    }


    public function items($cat_id)
    {
        $items = Warehouse::where('cat_id', $cat_id)->paginate(50);
        return view('provider.warehouse.index', compact('items'));
    }


    public function search()
    {
        $search = Input::get('search');
        $items = Warehouse::where(function($q) use($search)
            {
                $q->where('code','like','%'.$search.'%');
                $q->orWhere('en_name','like','%'.$search.'%');
                $q->orWhere('ar_name','like','%'.$search.'%');
            }
        )->paginate(50);

        return view('provider.warehouse.search', compact('items','search'));
    }


    public function create()
    {
        $cats = Category::where('parent_id', NULL)->get();
        return view('provider.warehouse.single', compact('cats'));
    }


    public function get_sub_cats($parent)
    {
        $sub_cats = Category::where('parent_id', $parent)->get();
        return response()->json($sub_cats);
    }


    public function store(Request $request)
    {
        $table = provider()->provider_id.'_warehouse_parts';
        $this->validate($request,
            [
                'code' => 'required|unique:'.$table.',code',
                'cat_id' => 'required|exists:categories,id',
                'en_name' => 'required',
                'ar_name' => 'required',
                'en_desc' => 'required',
                'ar_desc' => 'required',
                'count' => 'required',
                'price' => 'required',
                'image' => 'required|image'
            ]
        );

        $name = unique_file($request->image->getClientOriginalname());
        $request->image->move(base_path().'/public/warehouses/',$name);

        $warehouse = new Warehouse();
            $warehouse->code = $request->code;
            $warehouse->cat_id =  $request->cat_id;
            $warehouse->en_name =  $request->en_name;
            $warehouse->ar_name = $request->ar_name;
            $warehouse->en_desc = $request->en_desc;
            $warehouse->ar_desc = $request->ar_desc;
            $warehouse->count = $request->count;
            $warehouse->price = $request->price;
            $warehouse->image = $name;
        $warehouse->save();


        return redirect('/provider/warehouse/'.$request->cat_id.'/items')->with('success', 'Item created successfully');
    }


    public function edit($code, Request $request)
    {
        $request->merge(['item_code' => $code]);
        $warehouse = provider()->provider_id.'_warehouse_parts';

        $this->validate($request,
            [
                'item_code' => 'required|exists:'.$warehouse.',code'
            ]
        );

        $cats = Category::where('parent_id', NULL)->get();
        $item = Warehouse::where('code', $code)->first();
        return view('provider.warehouse.single', compact('cats','item'));
    }


    public function update(Request $request)
    {
        $table = provider()->provider_id.'_warehouse_parts';
        $this->validate($request,
            [
                'item_id' => 'required|exists:'.$table.',id',
                'code' => 'required|unique:'.$table.',code,'.$request->item_id,
                'cat_id' => 'sometimes|exists:categories,id',
                'en_name' => 'required',
                'ar_name' => 'required',
                'en_desc' => 'required',
                'ar_desc' => 'required',
                'price' => 'required',
                'count' => 'required',
                'image' => 'sometimes|image'
            ]
        );

        $item = Warehouse::where('id',$request->item_id)->first();
            $item->code = $request->code;
            if($request->cat_id) $item->cat_id = $request->cat_id;
            $item->en_name = $request->en_name;
            $item->ar_name = $request->ar_name;
            $item->en_desc = $request->en_desc;
            $item->ar_desc = $request->ar_desc;
            $item->count = $request->count;
            $item->price = $request->price;
            if($request->image)
            {
                unlink(base_path().'/public/warehouses/'.$item->image);
                $name = unique_file($request->image->getClientOriginalname());
                $request->image->move(base_path().'/public/warehouses/', $name);
                $item->image = $name;
            }
        $item->save();

        return redirect('/provider/warehouse/'.$item->cat_id.'/items')->with('success', 'Item updated successfully');
    }


    public function change_status(Request $request)
    {
        $table = provider()->provider_id.'_warehouse_parts';
        $this->validate($request,
            [
                'item_code' => 'required|exists:'.$table.',code',
            ]
        );

        $item = Warehouse::where('code', $request->item_code)->first();
            if($item->active == 1) $item->active = 0;
            else $item->active = 1;
        $item->save();

        return back()->with('success', 'Item updated successfully !');
    }


//    public function destroy(Request $request)
//    {
//        $table = provider()->provider_id.'_warehouse_parts';
//        $this->validate($request,
//            [
//                'item_code' => 'required|exists:'.$table.',code',
//            ]
//        );
//
//        Warehouse::where('code', $request->item_code)->delete();
//
//        return back()->with('success', 'Item deleted successfully');
//    }


    public function excel_view()
    {
        return view('provider.warehouse.upload');
    }



    public function excel_upload(Request $request)
    {
        $this->validate($request,
            [
                'file' => 'required|file'
            ]
        );

        $array = Excel::toArray(new WarehousesImport(),$request->file('file'));

        foreach($array[0] as $data)
        {
            $data = array_filter($data);
            if (count($data) > 0)
            {
                try
                {
                    $request->merge(['code' => $data[0],'cat_id' => $data[1], 'count' => $data[2],'price' => $data[3], 'en_name' => $data[4],'ar_name' => $data[5], 'en_desc' => $data[6], 'ar_desc' => $data[7]]);
                }
                catch (\Exception $e)
                {
                    return back()->with('error','Missing Column | '.$e->getMessage().',Offsets start from 0');
                }


                $this->validate($request,
                    [
                        'code' => 'required',
                        'cat_id' => 'required|exists:categories,id,type,2',
                        'count' => 'required|numeric',
                        'price' => 'required|numeric',
                        'en_name' => 'required',
                        'ar_name' => 'required',
                        'en_desc' => 'required',
                        'ar_desc' => 'required',
                    ],
                    [
                        'code.required' => 'Missing data in Code column.',
                        'cat_id.required' => 'Missing data in Category ID column.',
                        'cat_id.exists' => 'Invalid data in Category ID column,which is '.$request->cat_id,
                        'count.required' => 'Missing data in Count column.',
                        'count.numeric' => 'Invalid data in Count column,must be numeric value.',
                        'price.required' => 'Missing data in price column.',
                        'price.numeric' => 'Invalid data in Price column,must be numeric value.',
                        'en_name.required' => 'Missing data in English Name column.',
                        'ar_name.required' => 'Missing data in Arabic Name column.',
                        'en_desc.required' => 'Missing data in English Description column.',
                        'ar_desc.required' => 'Missing data in Arabic Description column.',
                    ]
                );



                $exist = Warehouse::where('code', $data[0])->first();

                if ($exist == NULL)
                {
                    $item = new Warehouse();
                        $item->code = $data[0];
                        $item->cat_id = $data[1];
                        $item->count = $data[2];
                        $item->price = $data[3];
                        $item->en_name = $data[4];
                        $item->ar_name = $data[5];
                        $item->en_desc = $data[6];
                        $item->ar_desc = $data[7];
                    $item->save();
                }
                else
                {
                    $item = Warehouse::where('code', $data[0])->first();
                        $item->cat_id = $data[1];
                        $item->count = $data[2];
                        $item->price = $data[3];
                        $item->en_name = $data[4];
                        $item->ar_name = $data[5];
                        $item->en_desc = $data[6];
                        $item->ar_desc = $data[7];
                    $item->save();
                }
            }
        }

        return redirect('/provider/warehouse/all')->with('success', 'Items uploaded successfully');
    }


    public function images_view()
    {
        return view('provider.warehouse.upload_images');
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
            $tmp_dir = base_path('/public/warehouses/'.provider()->provider_id.'_tmp_images');

            try
            {
                $zip->open($request->file);
                $zip->extractTo($tmp_dir);

                $images = array_diff(scandir($tmp_dir),['.','..']);

                foreach($images as $image)
                {
                    $explode = explode('.',$image);

                    $part = Warehouse::where('code', $explode[0])->first();

                    if($part)
                    {
                        $name = unique_file($image);

                        File::copy($tmp_dir.'/'.$image,base_path().'/public/warehouses/'.$name);
                        File::delete($tmp_dir.'/'.$image);

                        if($part->image != 'box.png') $old_image = $part->image;
                        $part->image = $name;
                        $part->save();

                        if(isset($old_image)) unlink(base_path().'/public/warehouses/'.$old_image);
                    }
                    else
                    {
                        return back()->with('error', 'Invalid Code for the image named '. $image);
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

        return redirect('/provider/warehouse/all')->with('success', 'Images uploaded & set successfully');
    }


    public function categories_excel_export()
    {
        $ids = ProviderSubscription::where('provider_id', provider()->provider_id)->select('subs')->first();

        if($ids == NULL) return back()->with('error','You have not assigned to categories subscriptions yet,contact customer support for more info !');

        $categories = Category::whereIn('id', unserialize($ids->subs))->select('id as ID','parent_id as Parent','en_name as Name')->get();

        foreach($categories as $category)
        {
            $category['Fee'] = ProviderCategoryFee::where('provider_id',provider()->provider_id)->where('cat_id', $category->ID)->select('fee')->first()->fee;
            $category['Parent'] = Category::where('id', $category->Parent)->select('en_name')->first()->en_name;

            unset($category->id);
        }

        $categories = $categories->toArray();
        $filename = 'qareeb_categories_data.xls';


        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");

        $heads = false;
        foreach($categories as $category)
        {
            if($heads == false)
            {
                echo implode("\t", array_keys($category)) . "\n";
                $heads = true;
            }
            {
                echo implode("\t", array_values($category)) . "\n";
            }
        }

        die();
    }


    public function parts_excel_export()
    {
        $parts = Warehouse::select('code as Code','active as Status','cat_id as Category','en_name as Name','price as Price','count as Count')->get();

        foreach($parts as $part)
        {
            if($part->Status == 1) $part['Status'] = 'Active'; else $part['Status'] = 'Suspended';
            $category = Category::where('id', $part->Category)->select('parent_id','en_name')->first();
            $part['Category'] = $category->parent->en_name . ' - ' . $category->en_name;
        }

        $parts = $parts->toArray();
        $filename = 'qareeb_warehouse_data.xls';


        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");

        $heads = false;
        foreach($parts as $part)
        {
            if($heads == false)
            {
                echo implode("\t", array_keys($part)) . "\n";
                $heads = true;
            }
            {
                echo implode("\t", array_values($part)) . "\n";
            }
        }

        die();
    }
}
