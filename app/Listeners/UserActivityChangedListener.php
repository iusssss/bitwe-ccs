<?php

namespace App\Listeners;

use App\Events\UserActivityChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserActivityChangedListener
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
     * @param  UserActivityChanged  $event
     * @return void
     */
    public function handle(UserActivityChanged $event)
    {
        //
    }
}
