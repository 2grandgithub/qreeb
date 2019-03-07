<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemRequestState extends Model
{
    protected $fillable =
        [
            'request_id','company_id','status','desc','provider_id'
        ];


//    public function the_request()
//    {
//        return $this->belongsTo(OrderTechRequest::class, 'request_id');
//    }
//
//
//    public function provider()
//    {
//        return $this->belongsTo(Provider::class, 'provider_id');
//    }
//
//
//    public function company()
//    {
//        return $this->belongsTo(Company::class, 'company_id');
//    }
}
