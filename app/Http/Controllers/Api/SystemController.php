<?php

namespace App\Http\Controllers\Api;

use App\SystemSetting;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Traits\AjaxFile;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class SystemController extends Controller
{
  use AjaxFile;

  public $respond;
  protected $folder = 'app';

  public function __construct(ResponseHelper $response)
  {
    $this->respond = $response;
  }

  public function index()
  {
    $system = SystemSetting::all()->first();
    return $this->respond->withSuccess(Response::HTTP_ACCEPTED, $data = $system);
  }

  public function updateAppBanner(Request $request)
  {
    $this->middleware('isAdmin');
    $system = SystemSetting::all()->first();
    if (!is_null($request->banner['data'])) {
      try {
        $banner = $this->saveFile($request->banner, $this->folder);
        if ($system->app_banner &&  $system->app_banner != '') {
          $this->deleteFile($system->app_banner);
        }
        $system->app_banner = $banner;
        $system->save();
      } catch (Exception $e) {
        return $this->respond->withError(Response::HTTP_INTERNAL_SERVER_ERROR, "error updating banner");
      }
      return $this->respond->withSuccess(Response::HTTP_ACCEPTED, $data = $system);
    }
    return $this->respond->withError(Response::HTTP_BAD_REQUEST, "banner cannot be empty");
  }

  public function updateAppLoginBanner(Request $request)
  {
    //TODO
  }

  public function updateAppSettings(Request $request)
  {

    $this->middleware('isAdmin');
    $system = SystemSetting::all()->first();
    $this->validate($request, [
      'app_name' => 'required|min:3',
    ]);
    $system->app_name = $request->app_name;
    $system->save();
  }
}
