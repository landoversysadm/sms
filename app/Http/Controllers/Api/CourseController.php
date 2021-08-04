<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Course;
use App\Enrollment;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{

  private $respond;

  public function __construct(ResponseHelper $response)
  {
    $this->respond = $response;
  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::with(['faculty', 'students', 'instructors'])
                  ->withCount('students')->orderBy('created_at', 'desc')
                  ->where('hide',false)->get();
        return response()->json([
            'status'=>'success',
            'message'=>'data retrieved successfully',
            'data'=>$courses
        ], Response::HTTP_OK);
    }

    public function courseReport()
    {
      $courses = Course::with('course_students.student')
                ->withCount('course_students')
                ->orderBy('created_at', 'desc')->get();
      return $this->respond->withSuccess((String)Response::HTTP_OK, $courses);
    }

    public function getImageUrl($url)
    {
        $url = Storage::url($url);
        echo $url;
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
     * @param  \App\Courses  $courses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Courses  $courses
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      $course = Course::findOrFail($request->courseId);
      $course->instructors()->detach();
      $course->students()->detach();
      $course->delete();
      return $this->respond->withSuccess((string)Response::HTTP_OK, $course);
    }


     /**
     * Returns list of courses except the one enrolled to by an authenticated
     * user
     *
     *
     * @return \Illuminate\Http\Response
     */
    // public function listCourse()
    // {
    //     if(Auth::check() && Auth::user()->role == 1 && !empty(Auth::user()->student->id)){
    //         $enrollments = Enrollment::where('student_id', Auth::user()->student->id)->get();

    //     }else{
    //         $courses = Course::with('faculty')->get();
    //     }

    //     return response()->json([
    //         'status'=>'success',
    //         'message'=>'data retrieved successfully',
    //         'data'=>$courses
    //     ], Response::HTTP_OK);
    // }
}
