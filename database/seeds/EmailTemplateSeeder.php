<?php

use Illuminate\Database\Seeder;
use App\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
        	[
        		'id' => 1, 
	        	'subject' => 'We are looking for another agent to handle your ticket', 
	        	'body' => "<p>Sorry, our agent wasn't able to solve your concern, please wait for a more suited agent to handle this ticket.</p>", 
	        	'ticket_status' => 1,
	        	'edited_by' => 0,
        	],
        	[
        		'id' => 2, 
	        	'subject' => 'Your ticket is still being process by our agent', 
	        	'body' => "<p>We'd love to hear more about your concern. Please reply with more details or wait for our reply.</p>", 
	        	'ticket_status' => 2,
	        	'edited_by' => 0,
        	],
        	[
        		'id' => 3, 
	        	'subject' => 'How would you rate the support you received? CDEC CS', 
	        	'body' => "<p>We'd love to hear what you think of our customer support on this ticket. Please take a moment to answer one simple question below:</p>", 
	        	'ticket_status' => 3,
	        	'edited_by' => 0,
        	],
        	[
        		'id' => 4, 
	        	'subject' => 'How would you rate the support you received? CDEC CS', 
	        	'body' => "<p>This ticket is closed.</p>", 
	        	'ticket_status' => 4,
	        	'edited_by' => 0,
        	],
        ];
		foreach ($items as $item) {
		    EmailTemplate::updateOrCreate(['id' => $item['id']], $item);
		}
    }
}
