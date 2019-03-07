<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable =
        [
            'parent_id','ar_name','en_name'
        ];


    public function sub_cats()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }


    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }


    public static function get_cat($id)
    {
        $category = Category::find($id);
        return $category->en_name;
    }


    public static function get_cat_all($id)
    {
        $category = Category::find($id);
        return $category;
    }


    public function items()
    {
        return $this->hasMany(Warehouse::class, 'cat_id');
    }


    public function cat_fee()
    {
        return $this->hasOne(ProviderCategoryFee::class, 'cat_id')->where('provider_id', provider()->provider_id);
    }
}
