<?php

namespace App\Traits;

use App\Course_student;
trait CourseStudent
{

  public function  studentInSession($courseId, $sessionId)
  {
    $courseStudent = Course_student::
      where(['course_id'=>$courseId],['sessionId'=>$sessionId])
      ->with('student.user','student.enrollments','student.enrollments.course')->get();
      return $courseStudent;
  }

}




?>
