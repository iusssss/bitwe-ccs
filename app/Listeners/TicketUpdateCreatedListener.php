<?php

namespace App\Listeners;

use App\Events\TicketUpdateCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketUpdateCreatedListener
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
     * @param  TicketUpdateCreated  $event
     * @return void
     */
    public function handle(TicketUpdateCreated $event)
    {
        //
    }
}
