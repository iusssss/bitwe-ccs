<?php

namespace App\Console\Commands;

use App\Ticket;
use Illuminate\Console\Command;

class CloseTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'close:tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close tickets that are inactive';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $yesterday = date("Y-m-d", strtotime('-2 days') );
        $updates = [];
        $tickets = Ticket::with('updates')->get();
        foreach ($tickets as $i => $ticket) {
            if ($ticket->updates[0]->created_at->format('Y-m-d') <= $yesterday)
                $updates[$i] = $ticket->updates[0];
        }
        foreach ($updates as $update) {
            $update->status = 4;
            $update->save();
        }
        // return $updates;
    }
}
