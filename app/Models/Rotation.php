<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rotation extends Model
{
    protected $fillable =
        [
            'provider_id','en_name','ar_name','from','to'
        ];


    public function technicians()
    {
        return $this->hasMany(Technician::class, 'rotation_id');
    }
}
