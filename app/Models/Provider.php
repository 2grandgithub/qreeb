<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model implements Authenticatable
{
    use AuthenticableTrait;

    protected $fillable =
        [
        'address_id','interest_fee','warehouse_fee','ar_name','en_name','ar_desc','en_desc','email','phones','logo','username','password'
        ];


    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }


    public function technicians()
    {
        return $this->hasMany(Technician::class, 'provider_id');
    }


    public function orders()
    {
        return $this->hasMany(Order::class, 'provider_id');
    }


    public function cat_fees()
    {
        return $this->hasMany(ProviderCategoryFee::class, 'provider_id');
    }


    public function admin()
    {
        return $this->hasOne(ProviderAdmin::class,'provider_id');
    }
}
