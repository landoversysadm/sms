<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course_student extends Model
{
    protected $table = "course_student";

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function assessment()
    {
        return $this->hasMany('App\Assessment');
    }

    public function session()
    {
        return $this->belongsTo('App\Session');
    }

    public static function boot()
    {
      parent::boot();
      Self::deleting(function(Course_student $course_student){
        foreach($course_student->assessment as $assessment){
          $assessment->delete();
        }
      });
    }
}
