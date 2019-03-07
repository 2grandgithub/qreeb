<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class CompanyAdmin extends Model implements Authenticatable
{
    use AuthenticableTrait;
    use HasRoles;


    protected $table = 'company_admins';
    protected $fillable =
        [
            'role','company_id','badge_id','active','name','username','password','email','phone','image'
        ];

    public function setRememberToken($value)
    {
        return null;
    }


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
