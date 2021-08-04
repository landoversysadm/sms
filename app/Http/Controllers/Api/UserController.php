<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Events\AdminCustomEvent;
use App\Events\AccountTerminatedEvent;

class UserController extends Controller
{

    public $respond;

    public function __construct(ResponseHelper $respond)
    {
        $this->respond = $respond;
    }
    //
    public function index()
    {
        $users = User::all();

        return response()->json(
            [
                'status' => 'success',
                'users' => $users->toArray()
            ], 200);
    }

    public function show(Request $request, $id)
    {
        $user = User::find($id);

        return response()->json(
            [
                'status' => 'success',
                'user' => $user->toArray()
            ], 200);
    }

    public function currentUser()
    {
        $user = Auth::user();
        $student = User::whereId($user->id)->with(['student'])->get();
        return $this->respond->withSuccess(Response::HTTP_OK, $data = $student);
    }

    public function deactivateUser(Request $request)
    {
        $user = User::FindOrFail($request->userId);
        $user->active = 0;
        $user->save();
        $message = ['subject'=>'Account Terminated', 'body'=>'Your account on LABS has been terminated by the school'];
        event(new AccountTerminatedEvent($user, $message));
        return $this->respond->withSuccess(Response::HTTP_OK, $data = $request->userId);
    }

    public function activateUser(Request $request)
    {
        $user = User::FindorFail($request->userId);
        $user->active = 1;
        $user->save();
        $message = ['subject'=>'Account Activated', 'body'=>'Your account on LABS has been activated by the school'];
        event(new AccountTerminatedEvent($user, $message));
        return $this->respond->withSuccess(Response::HTTP_OK, $data = $user);
    }
}

