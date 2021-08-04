<?php

namespace App\Http\Controllers\Api;

use App\Student;
use App\Course_student;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class CoursesStudentsController extends Controller
{
    public $respond;

    public function __construct(ResponseHelper $respond)
    {
        $this->respond = $respond;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courseStudents = Course_student::all();
        return $this->respond->withSuccess((string)Response::HTTP_OK, $data =  $courseStudents);
    }

    public function distinctStudent()
    {
        $courseStudents = Course_student::groupBy('student_id')->get();
        return $this->respond->withSuccess((string)Response::HTTP_OK, $data =  $courseStudents);
    }

    public function studentActiveCourse()
    {
        $user = Auth::user();
        $course = Course_student::where('student_id',$user->student->id)->with('course','session')->get();
        return $this->respond->withSuccess((string)Response::HTTP_OK, $data = $course);
    }

    /**
     * Gets all student currently enrolled in a
     * course session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sessionStudent(Request $request)
    {
      $students = Course_student::where([
                        'course_id'=>$request->courseId,'session_id'=>$request->sessionId
                      ])->with(['student.user','student.enrollments.course'])->latest()->get();
        return response()->json([
            'status'=>'success',
            'message'=>'data retrieved successfully',
            'data'=>$students
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Courses_students  $courses_students
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Courses_students $courses_students)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Courses_students  $courses_students
     * @return \Illuminate\Http\Response
     */
    public function destroy(Courses_students $courses_students)
    {
        //
    }



}
