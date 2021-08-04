<?php

namespace App\Listeners;

use App\Events\CourseEndedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\CourseStatusNotification;

class CourseEndedListener
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
     * @param  CourseEndedEvent  $event
     * @return void
     */
    public function handle(CourseEndedEvent $event)
    {
        $users = $event->users;
        $message = $event->message;
        Notification::send($users, new CourseStatusNotification($message));
    }
}
