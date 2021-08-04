<?php

namespace App\Listeners;

use App\Events\NewMaterialEvent;
use App\Notifications\NewMaterialNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NewMaterialListener
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
     * @param  NewMaterialEvent  $event
     * @return void
     */
    public function handle(NewMaterialEvent $event)
    {
        $users = $event->users;
        Notification::send($users, new NewMaterialNotification($event->courseTitle));
    }
}
