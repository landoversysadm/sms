<?php

namespace App\Http\Controllers\Api;

use Auth;
use Validator;
use App\Course;
use App\Payment;
use App\Student;
use App\Enrollment;
use App\Assessment;
use App\Course_student;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\CourseSession;
use App\Helpers\ResponseHelper;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Events\PaymentStatusChangedEvent;
use Symfony\Component\HttpFoundation\Response;
use function GuzzleHttp\json_encode;

class PaymentController extends Controller
{
    use CourseSession;

    public $respond;
    public $supportedFiles;

    /**
     * TODO : status integers should be changed
     * to constants to enforce code readability
     */

    public function __construct(ResponseHelper $respond)
    {
        $this->respond = $respond;
        $this->supportedFiles =  ["application/pdf"=>'.pdf', "image/png"=>'.png', "image/jpg"=>'.jpg', "image/jpeg"=>'.jpeg'];
    }

    public function index(Request $request)
    {
        $payment = Payment::with('enrollment.course', 'enrollment.student.user')->latest()->get();
        return $this->respond->withSuccess((string)Response::HTTP_OK, $data = $payment);
    }

    public function studentPayments(Request $request)
    {
        $user = Auth::user();
        $student = Student::with('payments.enrollment.course')->where('user_id', $user->id)->latest()->get();
        return $this->respond->withSuccess((string)Response::HTTP_OK, $data = $student);
    }

    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'validated'=>['required', Rule::in([0, 1,2])]
        ]);

        if($validate->fails())
        {
            return $this->respond->withError((string)Response::HTTP_BAD_REQUEST,$errors=$validate->errors());
        }
        $payment = Payment::whereId($request->paymentId)->with('enrollment')->first();

        if($request->validated == 2){
            $payment->enrollment->payment_status = 1;
            $payment->enrollment->save();
            $course_student = new Course_student();
            $course_student->course_id = $payment->enrollment->course_id;
            $course_student->student_id = $payment->enrollment->student_id;
            //changing status to string either "completed => 2" or "not completed => 1"
            $course_student->status = 1;
            $course = Course::find($payment->enrollment->course_id);
            $session = $this->currentSession($course);
            $course_student->session_id = $session->id;
            $course_student->save();

            Assessment::create([
                'course_student_id' => $course_student->id,
                'scores' => json_encode($this->initScoreSheet()),
            ]);
        }
       $payment->validated = $request->validated;
        $payment->admin_note = $request->adminNote;

        $payment->save();

        $payment = Payment::whereId($request->paymentId)->with(['enrollment.course', 'enrollment.student.user'])->get();
        event(new PaymentStatusChangedEvent($payment));

        return $this->respond->withSuccess((string)Response::HTTP_OK, $data = [$request->all(),$payment]);
    }

    public function initScoreSheet()
    {
      $initScore  = [];
      $scoreCount = 0;
      $top = Assessment::all();
      if(count($top) <1 ){
        return $initScore;
      }
        $scores = json_decode($top->first()->scores);
        $scoreCount = count($scores);
        for($i=0; $i<$scoreCount; $i++){
          $initScore[] = ["title"=>$scores[$i]->title,
                          "value"=>0,"resits"=>[] ];
        }
        return $initScore;
    }

    /**
     * This function enables student to be
     * able to re-upload slips, after admin rejection
     */

     public function repayCourse(Request $request)
     {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->get();
        if($student->isEmpty())
        {
            return $this->respond->withError(Response::HTTP_UNAUTHORIZED,'student profile is required to perform this operation');
        }
        $student = $student->first();
        $payment = $payment = Payment::whereId($request->paymentId)->first();

        $validate = Validator::make($request->all(), [
            'slip_upload' =>'required|array',
            'amount_paid' => 'required',
            'date_of_payment' => 'required',
            'payee_name' => 'required'
        ]);

        if($validate->fails()){
            return $this->respond->withError(Response::HTTP_BAD_REQUEST,$errors=$validate->errors());
        }

        $previousSlip = $payment->slip_upload;

        $this->deleteFile($previousSlip);

        $file = $request->slip_upload;
        $decodedFile = $this->base64_to_file($file['data']);
        $unique = Str::random(10);

        $fileName = $this->saveFile($unique, $decodedFile, $file);

        $payment->slip_upload = $fileName;
        $payment->amount_paid = $request->amount_paid;
        $payment->date_of_payment = $request->date_of_payment;
        $payment->payee_name = $request->payee_name;
        $payment->validated = 0;
        $payment->save();

        return $this->respond->withSuccess(Response::HTTP_CREATED, $data = $payment);
     }

    /**
     * Uploads payment slip for a course
     *
     * @param \Illuminate\Http\Request $request
     * @return App\Helpers\ResponseHelper
     */
    public function payCourse(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->get();
        if($student->isEmpty())
        {
            return $this->respond->withError(Response::HTTP_UNAUTHORIZED,'student profile is required to perform this operation');
        }
        $student = $student->first();
        $enrollment = Enrollment::where([
            ['student_id','=', $student->id ],
            ['course_id','=', $request->courseId],
            ['status', '=', 'approved']
        ])->get();
        if($enrollment->isEmpty())
        {
            return $this->respond->withError(Response::HTTP_UNAUTHORIZED,'Application is required before payment');
        }
        $validate = Validator::make($request->all(), [
            'slip_upload' =>'required|array',
            'amount_paid' => 'required',
            'date_of_payment' => 'required',
            'payee_name' => 'required'
        ]);
        if($enrollment->first()->status == 'approved'){
            if($validate->fails())
            {
                return $this->respond->withError(Response::HTTP_BAD_REQUEST,$errors=$validate->errors());
            }

            if(!$validate->fails())
            {
                $file = $request->slip_upload;
                $decodedFile = $this->base64_to_file($file['data']);
                $unique = Str::random(10);

                $fileName = $this->saveFile($unique, $decodedFile, $file);
                $payment = new Payment();
                $payment->enrollment_id = $enrollment->first()->id;
                $payment->slip_upload = $fileName;
                $payment->amount_paid = $request->amount_paid;
                $payment->date_of_payment = $request->date_of_payment;
                $payment->payee_name = $request->payee_name;
                $payment->save();

                return $this->respond->withSuccess(Response::HTTP_CREATED, $request->slip_upload);
            }
            return $this->respond->withError(Response::HTTP_EXPECTATION_FAILED,$errors="AN error occured while uploading file, try again");
        }

        return $this->respond->withError(Response::HTTP_UNAUTHORIZED,$errors="Application not yet approved for payment");

    }


    public function base64_to_file($data)
    {
        $file = explode(',',$data)[1];
        $file = str_replace(' ','+',$file);
        $decoded_file = \base64_decode($file);
        return $decoded_file;
    }

    public function saveFile($unique, $decoded_file, $file)
    {
        $file_name = '/payment_slips/'. $unique.$this->supportedFiles[$file['type']];
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
