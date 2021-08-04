<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    //
    protected $guarded = [];

    public function Course()
    {
        return $this->belongsTo('App\Course');
    }

    public function sesssion()
    {
        return $this->belongsTo('App\Session');
    }
}
