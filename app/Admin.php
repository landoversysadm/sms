<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Admin extends Model
{
    //
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password','activation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','activation_token','email_verified_at', 'api_token','role' ,
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('role', function(Builder $builder){
            $builder->where('role', '=', 2)->orWhere('role', '=', 4);
        });
    }
}
