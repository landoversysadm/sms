<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Faculty extends Model
{
  use SoftDeletes;

    //
    protected $fillable = ['name'];

    public function courses()
    {
        return $this->hasMany('App\Course');
    }

    public static function boot()
    {
      parent::boot();
      Self::deleting(function(Faculty $faculty){
        foreach($faculty->courses as $course){
          $course->delete();
        }
      });
    }
}
