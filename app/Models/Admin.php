<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Model implements Authenticatable
{
    use AuthenticableTrait;
    use HasRoles;


    public function setRememberToken($value)
    {
        return null;
    }
}
