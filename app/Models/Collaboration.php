<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collaboration extends Model
{
    protected $fillable =
        [
            'provider_id','company_id'
        ];

    public static function get_provider($id)
    {
        $provider = Provider::where('id', $id)->select('id','en_name')->first();
        return $provider;
    }


    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }


    public function orders_count($provider_id,$company_id)
    {
        $count = Order::where('provider_id', $provider_id)->where('company_id', $company_id)->count();
        return $count;
    }
}
