<?php

use Illuminate\Database\Seeder;
use App\TicketStatus;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
        	['id' => 1, 'name' => 'Open'],
        	['id' => 2, 'name' => 'Pending'],
        	['id' => 3, 'name' => 'Resolved'],
        	['id' => 4, 'name' => 'Closed'],
        ];
		foreach ($items as $item) {
		    TicketStatus::updateOrCreate(['id' => $item['id']], $item);
		}
    }
}
