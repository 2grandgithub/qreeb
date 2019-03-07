<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySubscription extends Model
{
    protected $table = 'company_subscriptions';
    protected $fillable =
        [
            'company_id','subs'
        ];


}
