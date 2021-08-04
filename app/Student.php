<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Student extends Model
{
  use SoftDeletes;

    protected $guarded = ['id'];

    public function enrollments()
    {
        return $this->hasMany('App\Enrollment')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function payments()
    {
      return $this->hasManyThrough('App\Payment', 'App\Enrollment');
    }

}
