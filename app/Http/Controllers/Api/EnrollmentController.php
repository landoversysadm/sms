<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Student;
use Validator;
use App\Enrollment;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Events\EnrollmentStatusChangedEvent;
use Symfony\Component\HttpFoundation\Response;


class EnrollmentController extends Controller
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
    $enrollments = Enrollment::with(['student.user', 'course', 'student.enrollments.course'])->latest()->get();
    return $this->respond->withSuccess(Response::HTTP_OK, $data = $enrollments);
  }

  public function getEnrollment(Request $request)
  {

    $enrollments = Enrollment::where('status', $request->status)->with(['student', 'course'])->get();
    return $this->respond->withSuccess(Response::HTTP_OK, $data = $enrollments);
  }

  public function singleEnrollment(Request $request)
  {
    $enrollments = Enrollment::whereId($request->enrollmentId)->with(['student', 'course', 'payment'])->get();
    return $this->respond->withSuccess(Response::HTTP_OK, $data = $enrollments);
  }

  public function getMyEnrollments(Request $request)
  {
    $user = Auth::user();
    $student = Student::where('user_id', $user->id)->get();
    if ($student->isEmpty()) {
      return $this->respond->withError(Response::HTTP_UNAUTHORIZED, 'student profile is required to perform this operation');
    }
    $enrollments = Enrollment::where('student_id', $student->first()->id)->with(['student.user', 'course.faculty', 'course.instructors', 'course.materials'])->get();
    return $this->respond->withSuccess(Response::HTTP_OK, $data = $enrollments);
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
   * @param  \App\Enrollments  $enrollments
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Enrollment $enrollments)
  {
    $validate = Validator::make($request->all(), [
      'status' => ['required', Rule::in(['pending', 'completed', 'terminated', 'approved', 'enrolled', 'not completed', 'declined'])],
    ]);

    if ($validate->fails()) {
      return $this->respond->withError(Response::HTTP_BAD_REQUEST, $errors = $validate->errors());
    }
    $enrollment = Enrollment::whereId($request->enrollmentId)->first();
    $enrollment->status = $request->status;
    $enrollment->admin_note = $request->admin_note;
    $enrollment->save();
    $enrollment = Enrollment::whereId($request->enrollmentId)->with(['student.user', 'course'])->get();
    event(new EnrollmentStatusChangedEvent($enrollment));
    return $this->respond->withSuccess(Response::HTTP_OK, $data = $enrollment);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Enrollments  $enrollments
   * @return \Illuminate\Http\Response
   */
  public function destroy(Enrollments $enrollments)
  {
    //
  }
}
