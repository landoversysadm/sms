<?php

namespace App\Http\Controllers\Api;

use Auth;
use Validator;
use App\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Events\AdminCustomEvent;
use App\CustomAdminNotification;
use Illuminate\Support\Collection;

class NotificationController extends Controller
{
    public $respond;

    public function __construct(ResponseHelper $respond)
    {
        $this->respond = $respond;
    }
    public function notifications(Request $request)
    {
        $user = Auth::user();
        return $this->respond->withSuccess(Response::HTTP_OK, $data = $user->notifications);
    }

    public function updateNotification(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'notificationId' => 'required'
        ]);
        foreach ($user->notifications as $notification) {
            if($notification->id == $request->notificationId){
                $notification->markAsRead();
            }
        }
        return $this->respond->withSuccess(Response::HTTP_OK, $data = $notification);
    }

    public function customNotificationFromAdmin(Request $request)
    {
        $this->validate($request,[
            'recipients' => 'required|array',
            'subject' => 'required|min:3',
            'body' => 'required|min:3'
        ]);

        $userIds = collect($request->recipients)->map(function($recipient){
            return $recipient['id'];
        });
        $userFullNames = collect($request->recipients)->map(function($recipient){
            return $recipient['name'];
        });

        $customNotifiaction = $this->sendCustomNotification($request, $userIds, $userFullNames);

        return $this->respond->withSuccess(Response::HTTP_OK, $customNotifiaction);
    }

    public function sendCustomNotification(Request $request, Collection $userIds, Collection $userFullNames)
    {
        $users = User::whereIn('id', $userIds)->get()->all();
        $message = ['subject'=>$request->subject,
                   'body'=>$request->body,
                   'from'=>$this->userRole(Auth::user()), 'toMail'=>$request->toMail];

        $customNotification = CustomAdminNotification::create([
            'subject' => $request->subject,
            'body' => $request->body,
            'user_id' => Auth::user()->id,
            'recipients' => $userFullNames
        ]);

         event(new AdminCustomEvent($users, $message));

         return $customNotification;
    }

    public function getCustomNotification(Request $request)
    {
        $user = Auth::user();
        return $this->respond->withSuccess(Response::HTTP_OK, CustomAdminNotification::where('user_id',$user->id)->latest()->get());
    }

    public function deleteCustomNotification(Request $request)
    {
      $customNotifiaction = CustomAdminNotification::findOrFail($request->id);
       $customNotifiaction->delete();
      return $this->respond->withSuccess((String)Response::HTTP_OK,'i received it');
    }

    public function userRole($user)
    {
      if($user->role === 3){
        return 'Instructor';
      }
      return 'Administrator';
    }
}
