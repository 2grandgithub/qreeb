<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class ProviderAdmin extends Model implements Authenticatable
{
    use AuthenticableTrait;
    use HasRoles;

    protected $table = 'provider_admins';
    protected $fillable =
        [
          'provider_id','badge_id','active','username','password','image','name','email','phone'
        ];

    public function setRememberToken($value)
    {
        return null;
    }

}
