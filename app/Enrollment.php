<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Enrollment extends Model
{
  use SoftDeletes;

    //

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function payment()
    {
        return $this->hasOne('App\Payment');
    }

    public function user()
    {
        return $this->hasOneThrough('App\User', 'App\Student');
    }
}
