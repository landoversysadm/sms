<?php

namespace App\Listeners;

use App\Events\AssessmentUploadedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssessmentUploadedListener
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
     * @param  AssessmentUploadedEvent  $event
     * @return void
     */
    public function handle(AssessmentUploadedEvent $event)
    {
        //
    }
}
