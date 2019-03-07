<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderSubscription extends Model
{
    protected $table = 'provider_subscriptions';
    protected $fillable =
        [
            'provider_id','subs'
        ];
}
