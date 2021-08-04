<?php

namespace App\Listeners;

use App\Events\NewEnrollmentEvent;
use App\Notifications\NewEnrollmentNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NewEnrollmentListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewEnrollmentEvent  $event
     * @return void
     */
    public function handle(NewEnrollmentEvent $event)
    {
      $admin_mail = config('system.school_email');
      Notification::route('mail', $admin_mail)->notify(new NewEnrollmentNotification());
    }
}
