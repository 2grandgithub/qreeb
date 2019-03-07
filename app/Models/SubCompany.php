<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCompany extends Model
{
    protected $fillable =
        [
            'parent_id','en_name','ar_name'
        ];


    public function users()
    {
        return $this->hasMany(User::class, 'sub_company_id');
    }
}
