<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTechDetail extends Model
{
    protected $fillable =
        [
            'order_id','type','desc','before_images','after_images'
        ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'type_id');
    }
}
