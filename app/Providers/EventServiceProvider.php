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
        'App\Events\TicketCreated' => [
            'App\Listeners\TicketCreatedListener',
        ],
        'App\Events\TicketUpdateCreated' => [
            'App\Listeners\TicketUpdateCreatedListener',
        ],
        'App\Events\UserActivityChanged' => [
            'App\Listeners\UserActivityChangedListener',
        ],
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
