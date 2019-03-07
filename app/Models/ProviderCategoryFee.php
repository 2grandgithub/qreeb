<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderCategoryFee extends Model
{
    protected $fillable =
        [
            'provider_id','cat_id','fee'
        ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }
}
