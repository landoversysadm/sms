<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\EnrollmentStatusChangedEvent' => [
            'App\Listeners\EnrollmentStatusChangedListener',
        ],
        'App\Events\PaymentStatusChangedEvent' => [
            'App\Listeners\PaymentStatusChangedListener',
        ],
        'App\Events\AdminCustomEvent' => [
            'App\Listeners\AdminCustomListener',
        ],
        'App\Events\CourseAddedEvent' => [
            'App\Listeners\CourseAddeddListener',
        ],
        'App\Events\AccountTerminatedEvent' => [
            'App\Listeners\AccountTerminatedListener'
        ],
        'App\Events\AssessmentUploadedEvent' => [
            'App\Listeners\AssessmentUploadedListener'
        ],
        'App\Events\CourseEndedEvent' => [
            'App\Listeners\CourseEndedListener'
        ],
        'App\Events\NewEnrollmentEvent' => [
            'App\Listeners\NewEnrollmentListener',
        ],
        'App\Events\NewMaterialEvent' => [
          'App\Listeners\NewMaterialListener',
      ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
