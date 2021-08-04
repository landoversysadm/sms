<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{


    //
    protected $guarded = [];

    public function courses()
    {
        return $this->belongsToMany('App\Course');
    }

    public function user()
    {
      return $this->belongsTo('App\User');
    }
}
