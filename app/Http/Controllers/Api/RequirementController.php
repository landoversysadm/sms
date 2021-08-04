<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequirementRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Requirement;

class RequirementController extends Controller
{
  public $respond;

  public function __construct(ResponseHelper $response)
  {
    $this->respond = $response;
  }

  public function index()
  {
    $requirements = Requirement::all();
    return response()->json($requirements, 200);
  }

  public function store(RequirementRequest $request)
  {
    $requirement = Requirement::create($request->all());
    return response()->json(['status' => 'success'], 200);
  }

  public function patch(RequirementRequest $request)
  {
    $requirement = Requirement::where('id', $request->requirementId)->update($request->all());
    return $this->respond->withSuccess(Response::HTTP_ACCEPTED, $data = $requirement);
  }

  public function destroy(Request $request)
  {
    $req = Requirement::where('id', $request->requirementId)->firstOrFail();
    $req->delete();
    return $this->respond->withSuccess((string) Response::HTTP_ACCEPTED, []);
  }
}
