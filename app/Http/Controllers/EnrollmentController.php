<?php

namespace App\Http\Controllers;

use Auth;
use App\Course;
use App\Student;
use App\User;

use App\Enrollment;
use App\Events\NewEnrollmentEvent;
use App\Http\Requests\EnrollmentStoreRequest;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $course = Course::where('id', $request->courseId)->first();
    $user = Auth::user();
    if ($user->role != '1') {
      return redirect('/');
    }
    $student = Student::where('user_id', $user->id)->get();
    if (!$student->isEmpty()) {
      $enrollment = Enrollment::where([
        ['student_id', '=', $student->first()->id],
        ['course_id', '=', $request->courseId],
      ])->get();
      if ($enrollment->isNotEmpty() && $enrollment->first()->status !== "declined") {
        return redirect('/');
      }
    }

    return view('enroll')->with(compact('course'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a new student enrollment.
   *
   * @param  \Illuminate\Http\EnrollmentStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(EnrollmentStoreRequest $request)
  {
    $user = Auth::user();
    $student = Student::where('user_id', $user->id)->get();
    if ($student->isEmpty()) {
      $student = new Student();
    } else {
      $student = $student->first();
    }
    $enrollment = Enrollment::where([
      ['student_id', '=', $student->id],
      ['course_id', '=', $request->courseId],
    ])->get();
    if ($enrollment->isNotEmpty() && $enrollment->first()->status !== "declined") {
      return redirect()->back()->withErrors(['error' => 'Already enrolled for the course'])->withInput();
    }
    try {
      $user->first_name = $request->firstname;
      $user->last_name = $request->surname;
      $user->midlle_name = $request->middlename ?? '';

      $student->user_id = $user->id;
      $student->date_of_birth = $request->dob;
      $student->title = $request->title;
      $student->place_of_birth = $request->pob;
      $student->residential_address = $request->rAddress;
      $student->mailing_address = $request->mAddress ?? '';
      $student->work_phone = $request->workPhone ?? '';
      $student->mobile_phone = $request->mobilePhone;
      $student->emergency_name = $request->emergency_name;
      $student->emergency_mobile_phone = $request->emergency_number;
      $student->emergency_relationship = $request->emergency_relationship;
      $student->emergency_email = $request->emergency_email;
      $student->residential_country = $request->rCountry;
      $student->residential_state = $request->rState;

      $student->ref_name1 = $request->ref_name1;
      $student->ref_relationship1 = $request->ref_relationship1;
      $student->ref_address1 = $request->ref_address1;
      $student->ref_number1 = $request->ref_number1;
      $student->ref_email1 = $request->ref_email1;

      $student->ref_name2 = $request->ref_name2;
      $student->ref_relationship2 = $request->ref_relationship2;
      $student->ref_address2 = $request->ref_address2;
      $student->ref_number2 = $request->ref_number2;
      $student->ref_email2 = $request->ref_email2;

      $student->title = $request->title;

      $student->alumni_course = json_encode($request->alumni_course);
      $student->alumni_date = json_encode($request->alumni_date);


      if ((null !== $request->passport) && $request->passport->isValid()) {
        $passportPath = $request->passport->store('/public/profile_pictures');
        $student->profile_picture_url = $passportPath;
      }

      $user->save();
      $student->save();

      $requirements = [];

      for ($i = 0; $i < count($request->requirement_file); $i++) {
        $requirement_path = $request->requirement_file[$i]->store('/public/requirements');
        $requirements[$request->requirement_title[$i]] = ['file' => $requirement_path, 'type' => 'file'];
      }
      if (isset($request->textQuestion)) {
        for ($i = 0; $i < count($request->textQuestion); $i++) {
          $requirements[$request->textQuestion[$i]] = ['response' => $request->textResponse[$i], 'type' => 'text'];
        }
      }

      if (isset($request->check)) {
        for ($i = 0; $i < count($request->check); $i++) {
          $requirements[$request->checkTitle[$i]] = ['response' => $request->check[$i], 'type' => 'check'];
        }
      }

      $enrollment = new Enrollment();
      $enrollment->status = 'pending';
      $enrollment->student_id = $student->id;
      $enrollment->course_id = $request->courseId;
      $enrollment->requirement_uploads = json_encode($requirements);


      $enrollment->save();
      event(new NewEnrollmentEvent($enrollment));
      return redirect('/student')->with(['new_enrollment' => 'Enrollment successfull']);
    } catch (Exception $e) {
      return redirect()->back()->withInput()->with(['error' => 'true']);
    }
  }
}
