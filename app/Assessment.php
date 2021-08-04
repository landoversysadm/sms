<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $guarded = ['id'];

    public function CourseStudent()
    {
        return $this->belongsTo('App\Course_student');
    }
}
