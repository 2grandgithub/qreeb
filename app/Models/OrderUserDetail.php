<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderUserDetail extends Model
{
    protected $fillable =
        [
            'order_id','place','part','desc','images'
        ];
}
