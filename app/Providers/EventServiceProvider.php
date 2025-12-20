<?php

namespace App\Providers;

use App\Events\InstructorRegistered;
use App\Events\OrderConfirmed;
use App\Events\OrderPlaced;
use App\Listeners\NotifyInstructorsOfNewOrder;
use App\Listeners\SendInstructorWelcomeEmail;
use App\Listeners\SendOrderConfirmationEmail;
use App\Listeners\SendOrderConfirmedNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderPlaced::class => [
            SendOrderConfirmationEmail::class,
            NotifyInstructorsOfNewOrder::class,
        ],
        OrderConfirmed::class => [
            SendOrderConfirmedNotification::class,
        ],
        InstructorRegistered::class => [
            SendInstructorWelcomeEmail::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
