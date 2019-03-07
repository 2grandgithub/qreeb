<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable =
        [
            'parent_id','ar_name','en_name'
        ];


    public function cities()
    {
        return $this->hasMany(Address::class, 'parent_id');
    }


    public function parent()
    {
        return $this->belongsTo(Address::class, 'parent_id');
    }


    public static function get_address($id)
    {
        $address = Address::find($id);
        return $address->en_name;
    }
}
