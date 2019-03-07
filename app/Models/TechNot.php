<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechNot extends Model
{
    protected $table = 'tech_nots';
    protected $fillable =
        [
            'tech_id','order_id','ar_text','en_text'
        ];
}
