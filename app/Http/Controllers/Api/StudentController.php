<?php

namespace App\Http\Controllers\Api;

use Auth;
use Validator;
use App\Helpers\ResponseHelper;
use App\Student;
use App\Enrollment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\ResetsPasswords;

class StudentController extends Controller
{

  use ResetsPasswords;


  public $respond;
  public $supportedFiles;

  public function __construct(ResponseHelper $response)
  {
    $this->respond = $response;
    $this->supportedFiles =  ["image/jpeg" => '.jpg', "application/pdf" => '.pdf', "image/png" => '.png', "image/jpeg" => '.jpg'];
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $students = Student::with(['user', 'enrollments.course', 'user.notifications'])->latest()->get();
    return response()->json([
      'status' => 'success',
      'message' => 'data retrieved successfully',
      'data' => $students
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
    $id = Auth::user()->id;
    $studentExist = Student::where('user_id', $id)->get();
    if ($studentExist->isNotEmpty()) {
      return $this->respond->withError(Response::HTTP_CONFLICT, 'student profile exists already');
    }
    $validate = Validator::make($request->all(), [
      'sex' => 'required',
      'profile_picture' => 'required|image',
      'date_of_birth' => 'required|date'
    ]);

    if ($validate->fails()) {
      return $this->respond->withError(Response::HTTP_BAD_REQUEST, $errors = $validate->errors());
    }

    if ($request->profile_picture->isValid()) {
      $profilePath = $request->profile_picture->store('/profile_pictures');
    }

    $student = new Student();
    $student->user_id = $id;
    $student->profile_picture_url = $profilePath;
    $student->sex = $request->sex;
    $student->date_of_birth = $request->date_of_birth;
    $student->save();

    $this->respond->withSuccess(Response::HTTP_CREATED, $data = $student);
  }

  /**
   * Display the current user student profile.
   *
   * @return \Illuminate\Http\Response
   */
  public function profile()
  {
    $user = Auth::user();
    $student = Student::where('user_id', $user->id)->with('user')->get();
    if ($student->isEmpty()) {
      return $this->respond->withError(Response::HTTP_UNAUTHORIZED, 'student profile is required to perform this operation');
    }

    return $this->respond->withSuccess(Response::HTTP_OK, $data = $student);
  }


  /**
   * Specifies the section of the user to be updated
   *
   * @param \Illuminate\Http\Request
   */
  public function update(Request $request)
  {
    $user = Auth::user();
    $student = Student::where('user_id', $user->id)->with('user')->get();
    if ($student->isEmpty()) {
      return $this->respond->withError(Response::HTTP_UNAUTHORIZED, 'student profile is required to perform this operation');
    }

    $this->validate($request, [
      'section' => ['required', Rule::in(['emergencyInfo', 'referenceInfo', 'personalInfo', 'password'])]
    ]);

    return $this->{$request->section . 'Update'}($request, $user, $student->first());
  }

  /**
   * Update personal information of student
   *
   * @param \Illuminate\Http\Request
   */
  public function personalInfoUpdate(Request $request, $user, $student)
  {
    $this->validate($request, [
      'title' => 'required',
      'surname' => 'required',
      'firstname' => 'required',
      'middlename' => 'required',
      'dob' => 'required',
      'pob' => 'required',
      'rAddress' => 'required',
      'mAddress' => 'required',
      'workPhone' => 'required|numeric',
      'mobilePhone' => 'required|numeric',
    ]);

    $user->first_name = $request->firstname;
    $user->last_name = $request->surname;
    $user->midlle_name = $request->middlename;
    $student->date_of_birth = $request->dob;
    $student->title = $request->title;
    $student->place_of_birth = $request->pob;
    $student->residential_address = $request->rAddress;
    $student->mailing_address = $request->mAddress;
    $student->work_phone = $request->workPhone;
    $student->mobile_phone = $request->mobilePhone;
    $student->residential_country = $request->rCountry;
    $student->residential_state = $request->rState;

    $user->save();
    $student->save();

    return $this->respond->withSuccess(Response::HTTP_ACCEPTED, $data = $student);
  }

  /**
   * Update reference information of student
   *
   * @param \Illuminate\Http\Request
   */
  public function referenceInfoUpdate(Request $request, $user, $student)
  {
    $this->validate($request, [
      'name1' => 'required',
      'name2' => 'required',
      'relationship1' => 'required',
      'relationship2' => 'required',
      'address1' => 'required',
      'address2' => 'required',
      'number1' => 'required',
      'number2' => 'required',
      'email1' => 'required|email',
      'email2' => 'required|email',
    ]);

    $student->ref_name1 = $request->name1;
    $student->ref_relationship1 = $request->relationship1;
    $student->ref_address1 = $request->address1;
    $student->ref_number1 = $request->number1;
    $student->ref_email1 = $request->email1;

    $student->ref_name2 = $request->name2;
    $student->ref_relationship2 = $request->relationship2;
    $student->ref_address2 = $request->address2;
    $student->ref_number2 = $request->number2;
    $student->ref_email2 = $request->email2;

    $student->save();

    return $this->respond->withSuccess(Response::HTTP_ACCEPTED, $data = $student);
  }

  /**
   * Update emergency information of user
   *
   * @param \Illuminate\Http\Request
   */
  public function emergencyInfoUpdate(Request $request, $user, $student)
  {

    $this->validate($request, [
      'name' => 'required',
      'relationship' => 'required',
      'mobile_phone' => 'required|numeric',
      'email' => 'required|email',
    ]);

    $student->emergency_name = $request->name;
    $student->emergency_mobile_phone = $request->mobile_phone;
    $student->emergency_relationship = $request->relationship;
    $student->emergency_email = $request->email;

    $student->save();

    return $this->respond->withSuccess(Response::HTTP_ACCEPTED, $data = $student);
  }

  /**
   * Update password of user
   *
   * @param \Illuminate\Http\Request
   */
  public function passwordUpdate(Request $request, $user, $student)
  {
    $this->validate($request, [
      'password' => [
        'bail', 'required',
        function ($attribute, $value, $fail) use ($user) {
          if (!Hash::check($value, $user->makeVisible('password')->password)) {
            $fail($attribute . ' is not the the current password');
          }
        }
      ],
      'newPassword' => 'required|min:6',
      'confirmPassword' => 'required|same:newPassword'

    ]);

    Auth::user()->fill([
      'password' => Hash::make($request->newPassword)
    ])->save();


    return $this->respond->withSuccess(Response::HTTP_ACCEPTED, $data = $student);
  }

  /**
   * Update user's profile picture
   *
   * @param \Illuminate\Http\Request
   */
  public function updateProfilePicture(Request $request)
  {
    if ($request->passport !== '') {
      $decoded_file = $this->base64_to_file($request->passport['data']);
      $unique = Str::random(16);

      $file_name = $this->saveFile($unique, $decoded_file, $request->passport['type'], 'profile_pictures');
      $user = Auth::user();
      $student = Student::where('user_id', $user->id)->first();
      $this->deleteFile($student->profile_picture_url);
      $student->profile_picture_url = $file_name;
      $student->save();

      return $this->respond->withSuccess(Response::HTTP_ACCEPTED, $data = $student);
    }

    return $this->respond->withError(Response::HTTP_EXPECTATION_FAILED, 'provide a valid profile picture');
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Students  $students
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
    $user = Auth::user();
    $student = Student::where('user_id', $user->id)->get();
    if ($student->isEmpty()) {
      return $this->respond->withError(Response::HTTP_UNAUTHORIZED, 'student profile is required to perform this operation');
    }

    $student->delete();

    return $this->respond->withSuccess(Response::HTTP_Ok);
  }

  /**
   * @param \Illuminate\Http\Request $request
   */
  public function applyCourse(Request $request)
  {
    $courseId = $request->courseId;
    $user = Auth::user();
    $student = Student::where('user_id', $user->id)->get();
    if ($student->isEmpty()) {
      return $this->respond->withError(Response::HTTP_UNAUTHORIZED, 'student profile is required to perform this operation');
    }
    $student = $student->first();
    $enrollment = Enrollment::where([
      ['student_id', '=', $student->id],
      ['course_id', '=', $request->courseId],
    ])->get();
    if ($enrollment->isNotEmpty()) {
      return $this->respond->withError(Response::HTTP_UNAUTHORIZED, 'Already applied for this course');
    }
    $validate = Validator::make($request->all(), [
      'requirementFiles' => 'required|array',
    ]);
    if ($validate->fails()) {
      return $this->respond->withError(Response::HTTP_BAD_REQUEST, $errors = $validate->errors());
    }

    /**
     * This is the array of course requirements and
     * upload name that would be saved into the database
     */
    $files = [];
    foreach ($request->requirementFiles as $file) {
      $decoded_file = $this->base64_to_file($file['data']);
      $unique = Str::random(10);



      $file_name = $this->saveFile($unique, $decoded_file, $file, 'requirements');
      $files[$file['name']] = $file_name;

      /**
       * considering the number of checks the file upload
       * had gone through in JS, we're rest assured there's no need
       * for error catching here.
       */
    }

    if (count($files) > 0) {
      $enrollment = new Enrollment();
      $enrollment->status = 'pending';
      $enrollment->student_id = $student->id;
      $enrollment->course_id = $courseId;
      $enrollment->requirement_uploads = json_encode($files);
      $enrollment->save();
      return $this->respond->withSuccess(Response::HTTP_CREATED, $enrollment);
    }


    return $this->respond->withError(Response::HTTP_EXPECTATION_FAILED, 'Error saving file, please try again');
  }



  /**
   * fetches the enrollment history of a user
   *
   *  @param \Illuminate\Http\Request $request
   * @return \App\Helpers\ResponseHelper
   */
  public function enrollmentHistory(Request $request)
  {
    $user = Auth::user();
    $student = Student::where('user_id', $user->id)->get();
    if ($student->isEmpty()) {
      return $this->respond->withError(Response::HTTP_UNAUTHORIZED, 'student profile is required to perform this operation');
    }
    $enrollments =  $student = Student::where('user_id', $user->id)->first()->enrollments;
    return $this->respond->withSuccess(Response::HTTP_OK, $data =  $enrollments);
  }

  /**
   * fetches the enrollment history of a user
   *
   *  @param \Illuminate\Http\Request $request
   * @return \App\Helpers\ResponseHelper
   */
  public function getCourses(Request $request)
  {
    // this is incomplete
    $user = Auth::user();
    $student = Student::where('user_id', $user->id)->get();
    if ($student->isEmpty()) {
      return $this->respond->withError(Response::HTTP_UNAUTHORIZED, 'student profile is required to perform this operation');
    }
  }

  public function getStudent(Request $request)
  {
    $student = Student::where('id', $request->studentId)->firstOrFail();
    return $this->respond->withSuccess(Response::HTTP_OK, $data = $tudent);
  }

  public function notifications(Request $request)
  {
    $user = Auth::user();
    return $this->respond->withSuccess(Response::HTTP_OK, $data = $user->notifications);
  }

  /**
   *
   */
  public function base64_to_file($data)
  {
    $file = explode(',', $data)[1];
    $file = str_replace(' ', '+', $file);
    $decoded_file = \base64_decode($file);
    return $decoded_file;
  }

  public function saveFile($unique, $decoded_file, $type, $folder)
  {
    $file_name = '/' . $folder . '/' . $unique . $this->supportedFiles[$type];
    Storage::disk('local')->put('/public/' . $file_name, $decoded_file);
    return $file_name;
  }

  public function deleteFile($fullPath)
  {
    try {
      Storage::disk('local')->delete('/public/' . $fullPath);
    } catch (FileNotFoundException $e) {
    }
    return true;
  }
}
