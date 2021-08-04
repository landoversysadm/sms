<?php

namespace App\Http\Controllers\Api;

use Validator;
use Exception;
use App\Faculty;
use App\Course;
use App\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\ResponseHelper;
use App\Traits\CourseSession;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Log\Logger;
use App\Course_student;
use App\Events\CourseEndedEvent;
use Illuminate\Database\Eloquent\Builder;

class FacultyController extends Controller
{
    use CourseSession;

    public $respond;
    public $supportedFiles;
    public static $courseStudentStatus_ACTIVE = 1;
    public static $courseStudentStatus_COMPLETED = 2;
    public static $courseStudentStatus_TERMINATED = 0;



    public function __construct(ResponseHelper $respond)
    {
        $this->respond = $respond;
        $this->supportedFiles =  ['application/pdf'=>'.pdf', 'image/png'=>'.png','image/jpeg'=>'.jpg', 'image/jpg'=>'.jpg'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faculty = Faculty::with(['courses','courses.instructors', 'courses.materials.user', 'courses.sessions', 'courses.students' => function ($query){
          $query->where('status', self::$courseStudentStatus_ACTIVE);
        }])->orderByDesc('created_at')->get();
        return response()->json([
            'status'=>'success',
            'message'=>'data retrieved successfully',
            'data'=>$faculty
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
        $this->validate($request,[
            'name'=>'required|min:3|string'
        ]);
        $faculty = Faculty::create(['name'=>$request->name]);
        return $this->respond->withSuccess(Response::HTTP_CREATED, $data=$faculty);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faculty $faculty)
    {
        $faculty = Faculty::find($request->facultyId);
        $validate = Validator::make($request->all(),[
            'name'=>'required|string'
            ]);
        if($validate->fails())
        {
            return $this->respond->withError(Response::HTTTP_BAD_REQUEST, $errors=$validate->errors());
        }
        $faculty->name = $request->name;
        $faculty->save();
        return $this->respond->withSuccess(Response::HTTP_ACCEPTED, $data = $faculty);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $faculty = Faculty::find($request->facultyId);
        try{
            $faculty->delete();
        }catch(Exception $e){
            return $this->respond->withError((string)Response::HTTP_EXPECTATION_FAILED, $errors=null);
        }
        return $this->respond->withSuccess((string)Response::HTTP_OK, 'successfull');
    }

    /**
     *
     */
    public function allCourse(Request $request)
    {
        $faculty = Faculty::where('id',$request->facultyId)->with('courses')->get();
        return $this->respond->withSuccess((string)Response::HTTP_OK, $faculty);
    }

    public function startCourseSession(Request $request)
    {
        $course = Course::whereId($request->courseId)->first();
        $this->validate($request,[
            'start_date'=>'required|date',
            'end_date'=>'required|date',
        ]);

        if(!empty($request->schedule)){
            $course->schedule  = $request->schedule;
        }
        $session = $this->currentSession($course);
        $courseStudent = Course_student::where('course_id',$course->id)->where('session_id',$session->id)->with('student.enrollments', 'student.user')->get();

        $courseStudent->each(function($cs){
            $cs->status = self::$courseStudentStatus_COMPLETED;
            if(isset($cs->student)){
                $cs->student->enrollments->each(function($e){
                $e->status = 'completed';
                $e->save();
              });
            }
            $cs->student->enrollments->each(function($e){
                $e->status = 'completed';
                $e->save();
            });
        });
        $course->start_date = $request->start_date;
        $course->end_date = $request->end_date;
        $course->save();

        $session = $this->newSession($course);
        if(isset($courseStudent->student)){
          $users = $courseStudent->student->user;
          event(new CourseEndedEvent($users, $course));
        }

        return $this->respond->withSuccess((string)Response::HTTP_CREATED, $data=$course);
    }

    /**
     *
     */
    public function addCourse(Request $request, Faculty $faculty)
    {
        try{
            $faculty = Faculty::findOrFail($request->facultyId);
        }catch (Exception $e){
            return $this->respond->withError(Response::HTTP_NOT_FOUND,"model not found");
        }

        $this->validate($request,[
            'title'=>'required|string|min:3',
            'price'=>'required|int|min:3',
            'banner'=>'required|array',
            'description'=>'required|string|min:3',
            'start_date'=>'required|date',
            'end_date'=>'required|date',
            'requirement'=>'array|required|min:1',
            'instructors'=>'array|required|min:1',
            'hide'=> 'required|boolean',
            'coming_soon'=> 'required|boolean'
        ]);

        $file = $request->banner;
        $decodedFile = $this->base64_to_file($file['data']);
        $unique = Str::random(10);
        $fileName = $this->saveFile($unique, $decodedFile, $file['type'], 'courseBanners');

        $ids = [];
        foreach($request->instructors as $instructor){
            $ids[] = $instructor['id'];
        }

        $course = new Course();
        $course->banner = $fileName;
        $course->title = $request->title;
        $course->price = $request->price;
        $course->faculty_id = $faculty->id;
        $course->end_date = $request->end_date;
        $course->schedule = $request->schedule;
        $course->start_date = $request->start_date;
        $course->description = $request->description;
        $course->hide = $request->hide;
        $course->coming_soon = $request->coming_soon;
        $course->requirement  = json_encode($request->requirement);
        $course->save();
        $course->instructors()->sync($ids);
        $session = $this->newSession($course);

        return $this->respond->withSuccess((string)Response::HTTP_CREATED, $data=$course);
    }

        /**
     * Update profile picture of user
     *
     * @param \Illuminate\Http\Request
     */
    public function updateCourseBanner(Request $request)
    {

        if($request->banner !== '') {
            $decoded_file = $this->base64_to_file($request->banner['data']);
            $unique = Str::random(16);

            $file_name = $this->saveFile($unique, $decoded_file, $request->banner['type'], 'courseBanners');
            $course = Course::findorFail($request->courseId);
            $this->deleteFile($course->banner);
            $course->banner = $file_name;
            $course->save();

            return $this->respond->withSuccess(Response::HTTP_ACCEPTED, $data=$course);
        }

        return $this->respond->withError(Response::HTTP_EXPECTATION_FAILED,'provide a valid profile picture');
    }


    public function deleteCourse(Request $request)
    {
        $courseId = Course::find($request->courseId);
        try{
            $course->delete();
        }catch(Exception $e){
            return $this->respond->withError(Response::HTTP_EXPECTATION_FAILED, $errors=null);
        }
        return $this->respond->withSuccess(Reponse::HTTP_OK);
    }

    /**
     * This method updates the course details
     * except the course requirement and course banner
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Helpers\ResponseHelper
     */
    public function updateCourse(request $request)
    {
        $course = Course::whereId($request->courseId)->with('instructors')->first();
        $this->validate($request,[
            'title'=>'required|string|min:3',
            'price'=>'required|int|min:3',
            'description'=>'required|string|min:3',
            'start_date'=>'required|date',
            'end_date'=>'required|date',
            'requirement'=>'array|required|min:1',
            'instructors'=>'array|required|min:1',
            'hide'=>'required|boolean',
            'coming_soon'=> 'required|boolean'
        ]);

        $ids = [];
        foreach($request->instructors as $instructor){
            $ids[] = $instructor['id'];
        }

        $course->title = $request->title;
        $course->price = $request->price;
        $course->end_date = $request->end_date;
        $course->schedule = $request->schedule;
        $course->start_date = $request->start_date;
        $course->description = $request->description;
        $course->hide = $request->hide;
        $course->coming_soon = $request->coming_soon;
        $course->requirement  = json_encode($request->requirement);
        $course->save();
        $course->instructors()->sync($ids);
        $session = $this->updateSession($course);


        return $this->respond->withSuccess((string)Response::HTTP_ACCEPTED, $data=$course);
    }

    public function base64_to_file($data)
    {
        $file = explode(',',$data)[1];
        $file = str_replace(' ','+',$file);
        $decoded_file = \base64_decode($file);
        return $decoded_file;
    }

    public function saveFile($unique, $decoded_file, $type, $folder)
    {
        $file_name = '/'.$folder.'/'. $unique.$this->supportedFiles[$type];
        Storage::disk('local')->put('/public/'.$file_name, $decoded_file);
        return $file_name;
    }

    public function deleteFile($fullPath)
    {
        try{
            Storage::disk('local')->delete('/public/'.$fullPath);
        }catch(FileNotFoundException $e){

        }
        return true;

    }
}
