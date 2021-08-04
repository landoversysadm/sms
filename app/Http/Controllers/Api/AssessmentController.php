<?php

namespace App\Http\Controllers\Api;

use App\Assessment;
use App\Course_student;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class AssessmentController extends Controller
{
    public $respond;

    public function __construct(ResponseHelper $response)
    {
        $this->respond = $response;
    }

    public function getAssessmentForCourse(Request $request)
    {
        $course_student = Course_student::where([
            'course_id'=>$request->courseId,'session_id'=>$request->sessionId
        ])->with(['student.user','assessment'])->latest()->get();

        return $this->respond->withSuccess((string)Response::HTTP_OK, $data= $course_student);

    }

    public function saveAssessment(Request $request)
    {
        $this->validate($request,[
            'scoreSheet' => 'required|array'
        ]);
       for($i=0; $i<count($request->scoreSheet); $i++)
       {
            $sheet = $request->scoreSheet[$i];
            $assessment = Assessment::find($sheet["id"]);
            $assessment->scores = json_encode($sheet["scores"]);
            $assessment->save();
       }
        return $this->respond->withSuccess((string)Response::HTTP_OK, $data=$request->scoreSheet);
    }

    public function getMyAssessment(Request $request)
    {
        $assessment = Assessment::where('course_student_id', $request->courseStudentId)->first();
        return $this->respond->withSuccess((string)Response::HTTP_OK, $data=$assessment);
    }
}
