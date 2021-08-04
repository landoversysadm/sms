<?php

namespace App\Http\Controllers\Api;

use App\Course;
use App\Events\NewMaterialEvent;
use App\Traits\AjaxFile;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Material;
use App\Traits\CourseSession;
use App\Traits\CourseStudent;
use App\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class MaterialController extends Controller
{
  use AjaxFile;
  use CourseSession;
  use CourseStudent;
  public $respond;


  public function __construct(ResponseHelper $respond)
  {
    $this->respond = $respond;
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'title' => 'required|string|min:3|unique:materials',
      'file' => 'required|array'
    ]);

    $file_url = $this->saveFile($request->file, 'materials');
    $userId = Auth::user()->id;
    $material = Material::create([
      'course_id' => $request->courseId,
      'user_id' => $userId,
      'title' => $request->title,
      'file_url' => $file_url,
    ]);

    $this->notifyStudents($request);

    return $this->respond->withSuccess((String)
     Response::HTTP_CREATED,$material);
  }

  public function notifyStudents(Request $request)
  {
    $userIds = [];
    $course = Course::findOrFail($request->courseId);
    $sessions = $this->studentInSession($request->courseId, $this->currentSession($course)->id);
    $userIds = collect($sessions)->map(function($session){
       return $session->student->user->id;
    });

    $users = User::whereIn('id', $userIds)->get()->all();
    event(new NewMaterialEvent($users, $course->title));
  }

  public function destroy(Request $request)
  {
    $material = Material::find($request->materialId);
    $this->deleteFile($material->file_url);
    $material->delete();
    return $this->respond->withSuccess((String)
      Response::HTTP_CREATED,[]);
  }


  public function index(Request $request)
  {

  }
}
