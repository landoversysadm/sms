<?php

namespace App\Listeners;

use App\Events\AdminCustomEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminCustomNotification;

class AdminCustomListener
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
     * @param  AdminCustomEvent  $event
     * @return void
     */
    public function handle(AdminCustomEvent $event)
    {
        $users = $event->users;
        $message = $event->message;
        Notification::send($users, new AdminCustomNotification($message));
    }
}
