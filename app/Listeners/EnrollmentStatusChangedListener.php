<?php

namespace App\Listeners;

use App\Events\EnrollmentStatusChangedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\EnrollmentStatusChangedNotification;

class EnrollmentStatusChangedListener
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
     * @param  EnrollmentStatusChangedEvent  $event
     * @return void
     */
    public function handle(EnrollmentStatusChangedEvent $event)
    {
        $enrollment = $event->enrollment->first();
        $user = $enrollment->student->user;
        $user->notify(new EnrollmentStatusChangedNotification($enrollment));
    }
}
