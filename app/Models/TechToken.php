<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechToken extends Model
{
    protected $table = 'tech_tokens';
    protected $fillable =
        [
            'tech_id','token'
        ];
}
