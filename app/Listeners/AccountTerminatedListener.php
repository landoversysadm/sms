<?php

namespace App\Listeners;

use App\Events\AccountTerminatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\AccountStatusNotification;
use Illuminate\Support\Facades\Notification;

class AccountTerminatedListener
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
     * @param  AccountTerminatedEvent  $event
     * @return void
     */
    public function handle(AccountTerminatedEvent $event)
    {
        $user = $event->user;
        $message = $event->message;
        Notification::send($user, new AccountStatusNotification($message));
    }
}
