<?php

namespace App\Listeners;

use App\Events\NewEnrollmentEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewEnrolledStudentListener
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

    }
}
