<?php

namespace App\Http\Controllers\Api;

use App\User;
use DateTime;
use App\Course;
use App\Instructor;
use Illuminate\Http\Request;
use App\Traits\CourseStudent;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class InstructorController extends Controller
{
    use CourseStudent;
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
        $instructor = Instructor::with('courses')->latest()->get();
        return $this->respond->withSuccess(
            (string) Response::HTTP_CREATED , $data=$instructor);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'firstName' => 'required',
            'lastName' => 'required',
            'password' => 'required|min:8',
        ]);

        $instructor = new Instructor();
        $instructor->email = $request->email;
        $instructor->first_name = $request->firstName;
        $instructor->last_name = $request->lastName;

        /**
         * Now register the instructor as user while
         * creating the instructor account
         */
        $user = User::create([
          'first_name' => $request->firstName,
          'last_name' => $request->lastName,
          'email' => $request->email,
          'password' => Hash::make($request->password),
          'role' => 3,
          'email_verified_at'=> (new DateTime('now')),
          'activation_token'=> '',
      ]);
      $user->active = 1;
      $user->save();

      $instructor->user_id = $user->id;
      $instructor->save();
      return $this->respond->withSuccess(
          (string)Response::HTTP_CREATED,$data=$instructor);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Instructor $instructor)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'firstName' => 'required',
            'lastName' => 'required',
            'middleName' => 'string|min:2'
        ]);
        if(is_null($request->instructorId)){
          $request->instructorId = Auth::user()->instructor->id;
        }
        $instructor = Instructor::find($request->instructorId);
        $instructor->email = $request->email;
        $instructor->first_name = $request->firstName;
        $instructor->last_name = $request->lastName;
        $instructor->save();

        $instructor->user->email = $request->email;
        $instructor->user->first_name = $request->firstName;
        $instructor->user->last_name = $request->lastName;
        $instructor->user->midlle_name = $request->middleName;
        $instructor->user->save();

        return $this->respond->withSuccess(
            (string)Response::HTTP_CREATED,$data=$instructor);
    }

    public function updatePassword(Request $request)
    {
      $user = Auth::user();
      $this->validate($request, [
          'password' => ['bail','required',
                             function($attribute, $value, $fail) use ($user){
                                  if(!Hash::check($value, $user->makeVisible('password')->password)){
                                      $fail($attribute.' is not the the current password');
                                  }
                             }
                          ],
          'newPassword' => 'required|min:6',
          'confirmPassword' => 'required|same:newPassword'
      ]);
      Auth::user()->fill([
           'password' => Hash::make($request->newPassword)
           ])->save();
      return $this->respond->withSuccess((string)Response::HTTP_ACCEPTED, $data = $user);
    }

    public function profile(Request $request)
    {
      $instructor = Instructor::where('user_id',Auth::User()->id)
                ->with('courses.instructors','courses.sessions', 'courses.students', 'courses.faculty', 'courses.materials.user', 'user')->get()->first();
        return $this->respond->withSuccess(
            (string) Response::HTTP_CREATED , $data=$instructor);
    }

    public function students(Request $request)
    {
      $students = $this->studentInSession($request->courseId,$request->sessionId);
      return $this->respond->withSuccess((string)Response::HTTP_OK,$students);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $instructor = Instructor::find($request->instructorId);
        foreach($instructor->courses as $course){
            $instructor->courses()->detach($course);
        }
        $instructor->delete();
        if(!is_null($instructor->user)){
          $instructor->user->delete();
        }
        return $this->respond->withSuccess(
            (string)Response::HTTP_OK);
    }
}
