<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Provider;
use App\Models\ProviderCategoryFee;
use App\Models\Technician;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use mysql_xdevapi\Exception;

class CategoryController extends Controller
{
    public function index($parent)
    {
        if($parent == 'all')
        {
            $categories = Category::where('parent_id', NULL)->paginate(50);
        }
        elseif($parent != 'search')
        {
            $categories = Category::where('parent_id', $parent)->paginate(50);
        }

        return view('admin.categories.index', compact('categories','parent'));
    }


    public function search()
    {
        $search = Input::get('search');
        $categories = Category::where(function ($q) use ($search)
            {
                $q->where('en_name','like','%'.$search.'%');
                $q->orWhere('ar_name','like','%'.$search.'%');
            }
        )->paginate(50);

        return view('admin.categories.index', compact('categories','search'));
    }


    public function main_create()
    {
        return view('admin.categories.main_single');
    }


    public function sub_create()
    {
        $categories = Category::where('parent_id', NULL)->get();
        return view('admin.categories.sub_single', compact('categories'));
    }


    public function sec_create()
    {
        $categories = Category::where('parent_id', NULL)->get();
        return view('admin.categories.sec_single', compact('categories'));
    }


    public function main_store(Request $request)
    {
        $this->validate($request,
            [

                'ar_name' => 'required|unique:categories',
                'en_name' => 'required|unique:categories',
                'image' => 'sometimes',
                'type' => 'required|in:1,2,3'
            ],
            [
                'ar_name.required' => 'Please enter an arabic name .',
                'ar_name.unique' => 'Arabic name already exists,please choose another one .',
                'en_name.required' => 'Please enter an english name .',
                'en_name.unique' => 'English name already exists,please choose another one .',
            ]
        );

        $category = new Category();
            $category->type = $request->type;
            $category->en_name = $request->en_name;
            $category->ar_name = $request->ar_name;
            if($request->image)
            {
                $name = unique_file($request->image->getClientOriginalName());
                $request->image->move(base_path().'/public/categories/',$name);
                $category->image = $name;
            }
        $category->save();

        if($category->type == 1)
        {
            return redirect('/admin/categories/all')->with('success', 'Created successfully !');
        }
        else
        {
            return redirect('/admin/categories/'.$category->parent_id)->with('success', 'Created successfully !');
        }
    }


    public function sub_store(Request $request)
    {
        $this->validate($request,
            [
                'parent_id' => 'sometimes|exists:categories,id',
                'ar_name' => 'required',
                'en_name' => 'required',
                'image' => 'sometimes',
                'price' => 'sometimes',
                'type' => 'required|in:1,2,3'
            ],
            [
                'parent_id.exists' => 'Please choose a country .',
                'ar_name.required' => 'Please enter an arabic name .',
                'en_name.required' => 'Please enter an english name .',
            ]
        );

        $category = new Category();
            $category->parent_id = $request->parent_id;
            $category->type = $request->type;
            $category->en_name = $request->en_name;
            $category->ar_name = $request->ar_name;
            $category->price = $request->price;
            if($request->image)
            {
                $name = unique_file($request->image->getClientOriginalName());
                $request->image->move(base_path().'/public/categories/',$name);
                $category->image = $name;
            }
        $category->save();

        $providers = Provider::select('id')->get();

        foreach($providers as $provider)
        {
            ProviderCategoryFee::updateOrcreate(
                [
                    'provider_id' => $provider->id,
                    'cat_id' => $category->id
                ],
                [
                    'fee' => $category->price
                ]
            );
        }

        if($category->type == 1)
        {
            return redirect('/admin/categories/all')->with('success', 'Created successfully !');
        }
        else
        {
            return redirect('/admin/categories/'.$category->parent_id)->with('success', 'Created successfully !');
        }
    }


    public function sec_store(Request $request)
    {
        $this->validate($request,
            [
                'parent_id' => 'sometimes|exists:categories,id',
                'ar_name' => 'required',
                'en_name' => 'required',
                'type' => 'required|in:1,2,3'
            ],
            [
                'parent_id.exists' => 'Please choose a country .',
                'ar_name.required' => 'Please enter an arabic name .',
                'en_name.required' => 'Please enter an english name .',
            ]
        );

        $category = new Category();
            $category->parent_id = $request->parent_id;
            $category->type = $request->type;
            $category->en_name = $request->en_name;
            $category->ar_name = $request->ar_name;
        $category->save();

        if($category->type == 1)
        {
            return redirect('/admin/categories/all')->with('success', 'Created successfully !');
        }
        else
        {
            return redirect('/admin/categories/'.$category->parent_id)->with('success', 'Created successfully !');
        }
    }


    public function main_edit($id)
    {
        $category = Category::find($id);
        return view('admin.categories.main_single', compact('category'));
    }


    public function sub_edit($id)
    {
        $category = Category::find($id);
        $categories = Category::where('parent_id', NULL)->get();

        return view('admin.categories.sub_single', compact('category','categories'));
    }


    public function sec_edit($id)
    {
        $category = Category::find($id);
        $categories = Category::where('parent_id', NULL)->get();

        return view('admin.categories.sec_single', compact('category','categories'));
    }


    public function main_update(Request $request)
    {
        $this->validate($request,
            [
                'category_id' => 'required|exists:categories,id',
                'ar_name' => 'required|unique:categories,ar_name,'.$request->category_id,
                'en_name' => 'required|unique:categories,en_name,'.$request->category_id,
                'image' => 'sometimes|image'
            ],
            [
                'ar_name.required' => 'Please enter an arabic name .',
                'ar_name.unique' => 'Arabic name already exists,please choose another one .',
                'en_name.required' => 'Please enter an english name .',
                'en_name.unique' => 'English name already exists,please choose another one .',
                'image.image' => 'Invalid image type .'
            ]
        );

        $category = Category::find($request->category_id);
            $category->en_name = $request->en_name;
            $category->ar_name = $request->ar_name;
            if($request->image)
            {
                $name = unique_file($request->image->getClientOriginalName());
                $request->image->move(base_path().'/public/categories/',$name);
                $category->image = $name;
            }
        $category->save();

        if($category->parent_id == NULL)
        {
            return redirect('/admin/categories/all')->with('success', 'Main category updated successfully !');
        }
        else
        {
            return redirect('/admin/categories/'.$category->parent_id)->with('success', 'Sub category updated successfully !');
        }
    }


    public function sub_update(Request $request)
    {
        $this->validate($request,
            [
                'category_id' => 'required|exists:categories,id',
                'parent_id' => 'required|exists:categories,id',
                'ar_name' => 'required',
                'en_name' => 'required',
                'image' => 'sometimes|image'
            ],
            [
                'parent_id.required' => 'Please choose a parent category .',
                'parent_id.exists' => 'Invalid parent category .',
                'ar_name.required' => 'Please enter an arabic name .',
                'en_name.required' => 'Please enter an english name .',
                'image.image' => 'Invalid image type .'
            ]
        );

        $category = Category::find($request->category_id);
            $category->parent_id = $request->parent_id;
            $category->en_name = $request->en_name;
            $category->ar_name = $request->ar_name;
            $category->price = $request->price;
            if($request->image)
            {
                $name = unique_file($request->image->getClientOriginalName());
                $request->image->move(base_path().'/public/categories/',$name);
                $category->image = $name;
            }
        $category->save();

        if($category->parent_id == NULL)
        {
            return redirect('/admin/categories/all')->with('success', 'Main category updated successfully !');
        }
        else
        {
            return redirect('/admin/categories/'.$category->parent_id)->with('success', 'Sub category updated successfully !');
        }
    }


    public function sec_update(Request $request)
    {
        $this->validate($request,
            [
                'category_id' => 'required|exists:categories,id',
                'parent_id' => 'sometimes|exists:categories,id',
                'ar_name' => 'required',
                'en_name' => 'required',
            ],
            [
                'parent_id.exists' => 'Invalid parent category .',
                'ar_name.required' => 'Please enter an arabic name .',
                'en_name.required' => 'Please enter an english name .',
            ]
        );

        $category = Category::find($request->category_id);
            if($request->parent_id != NULL)
            {
                $category->parent_id = $request->parent_id;
            }
            $category->en_name = $request->en_name;
            $category->ar_name = $request->ar_name;
        $category->save();

        if($category->parent_id == NULL)
        {
            return redirect('/admin/categories/all')->with('success', 'Main category updated successfully !');
        }
        else
        {
            return redirect('/admin/categories/'.$category->parent_id)->with('success', 'Sub category updated successfully !');
        }
    }


    public function destroy(Request $request)
    {
        $this->validate($request,
            [
                'cat_id' => 'required|exists:categories,id',
            ]
        );

        $category = Category::find($request->cat_id);

        if($category->type != 3)
        {
            if($category->type == 1 && $category->parent_id == NULL)
            {
                $subs = Category::where('parent_id', $category->id)->pluck('id');
            }
            else
            {
                $sub = Category::where('id',$request->cat_id)->first();
                $subs[] = $sub->id;
            }

            foreach($subs as $sub)
            {
                $techs = Technician::where('cat_ids','like','%'.$sub.'%')->get();

                foreach($techs as $tech)
                {
                    $explode = explode(',',$tech->cat_ids);
                    $the_rest = array_diff($explode,[$sub]);
                    $tech->cat_ids = implode(',',$the_rest);
                    $tech->save();
                }

            }
        }

        Category::where('id', $request->cat_id)->delete();

        return back()->with('success','Category deleted successfully !');
    }


    public function excel_export()
    {
        $categories = Category::select('id as ID','type','parent_id as Parent','en_name as Name','price as Fee')->get();

        foreach($categories as $category)
        {
            if($category->type == 1)
            {
                $type = 'Main';
                $fee = '-';
                $parent = '-';
            }
            elseif($category->type == 2)
            {
                $type = 'Sub';
                $fee = $category->Fee;
                $parent = Category::where('id', $category->Parent)->select('en_name')->first()->en_name . ' - ' . $category->Parent;
            }
            elseif($category->type == '3')
            {
                $type = 'Secondary';
                $fee = '-';
                $parent = Category::where('id', $category->Parent)->select('en_name')->first()->en_name . ' - ' . $category->Parent;
            }

            $category['Type'] = $type;
            $category['Fee'] = $fee;
            $category['Parent'] = $parent;

            unset($category->type);
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
}
