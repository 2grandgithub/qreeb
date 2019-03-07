<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRate extends Model
{
    protected $fillable =
        [
            'order_id', 'appearance', 'cleanliness', 'performance', 'commitment'
        ];
}
