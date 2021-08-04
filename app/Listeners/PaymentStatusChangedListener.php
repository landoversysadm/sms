<?php

namespace App\Listeners;

use App\Events\PaymentStatusChangedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\PaymentStatusChangedNotification;

class PaymentStatusChangedListener
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
     * @param  PaymentStatusChangedEvent  $event
     * @return void
     */
    public function handle(PaymentStatusChangedEvent $event)
    {
        $payment = $event->payment->first();
        $user = $payment->enrollment->student->user;
        $user->notify(new PaymentStatusChangedNotification($payment));
    }
}
