<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id','active','sub_company_id','badge_id', 'en_name', 'ar_name', 'email', 'password', 'lat', 'lng', 'phone', 'jwt', 'camp', 'street', 'plot_no', 'block_no', 'building_no', 'apartment_no', 'house_type'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];


    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }


    public function sub_company()
    {
        return $this->belongsTo(SubCompany::class, 'sub_company_id');
    }
}

