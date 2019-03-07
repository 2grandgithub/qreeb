<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    protected $fillable =
        [
            'address_id','interest_fee','ar_name','en_name','ar_desc','en_desc','email','phones','logo','item_limit'
        ];


    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }


    public function users()
    {
        return $this->hasMany(User::class, 'company_id');
    }


    public function orders()
    {
        return $this->hasMany(Order::class, 'company_id');
    }


    public function subscriptions()
    {
        return $this->hasOne(CompanySubscription::class, 'company_id');
    }


    public function admin()
    {
        return $this->hasOne(CompanyAdmin::class,'company_id');
    }
}
