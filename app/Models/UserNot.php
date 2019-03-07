<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNot extends Model
{
    protected $table = 'user_nots';
    protected $fillable =
        [
            'type','user_id','order_id','ar_text','en_text'
        ];
}
