<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Course extends Model
{
  use SoftDeletes;

    //
    protected $guarded = ['id'];

    public function faculty()
    {
        return $this->belongsTo('App\Faculty');
    }

    public function materials()
    {
      return $this->hasMany('App\Material');
    }

    public function students()
    {
        return $this->belongsToMany('App\Student');
    }

    public function instructors()
    {
        return $this->belongsToMany('App\Instructor');
    }

    public function sessions()
    {
        return $this->hasMany('App\Session');
    }

    public function enrollments()
    {
      return $this->hasMany('App\Enrollment');
    }

    public function course_students()
    {
      return $this->hasMany('App\Course_student');
    }

    //observe when ths model is being deleted and delete the child related models
    public static function boot ()
    {
      parent::boot();
      self::deleting(function (Course $course) {
        foreach ($course->sessions as $session)
        {
          $session->delete();
        }
        foreach ($course->enrollments as $enrollment){
          $enrollment->delete();
        }
      });
    }


}
