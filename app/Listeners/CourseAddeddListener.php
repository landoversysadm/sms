<?php

namespace App\Listeners;

use App\Events\CourseAddedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CourseAddeddListener
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
     * @param  CourseAddedEvent  $event
     * @return void
     */
    public function handle(CourseAddedEvent $event)
    {
        //
    }
}
