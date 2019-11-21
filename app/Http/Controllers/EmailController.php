<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Mail;
use App\EmailTemplate;
use App\TicketUpdate;
use App\Http\Resources\EmailTemplate as EmailResource;

class EmailController extends Controller
{
    public function sendMail(Request $request) {
    	$status_id = $request->input('status_id');
    	$template = EmailTemplate::FindOrFail($status_id);
    	$template->ticket_status = $template->ticketStatus;
    	$subject = $template->subject;
    	$body = $template->body;

    	$ticketNo = $request->input('ticketNo');
        $type = $request->input('type');
        $issue = $request->input('issue');
    	$to_name = $request->input('to_name');
    	$to_email = $request->input('to_email');
    	$from_email = $request->input('from_email');
        $updates = TicketUpdate::where('ticket_id', $ticketNo)->get();
    	$data = array(
    		'name' => $request->input('to_name'),
    		'ticketNo' => $ticketNo,
            'type' => $type,
            'issue' => $issue,
    		'body' => $body,
    		'ticketStatus' => $template->ticket_status->name,
            'agent' => auth()->user()->name,
            'updates' => $updates
		);
    	Mail::send('emails.mail', $data, function($message) use ($ticketNo, $subject, $to_name, $to_email, $from_email) {
    		$message->to($to_email, $to_name)
    				->subject("Ticket #{$ticketNo}: {$subject}");
    		$message->from($from_email, 'CDEC CS');
    	});
    }

    public function index() {
    	$emailTemplate = EmailTemplate::all();
    	foreach ($emailTemplate as $email) {
    		$email->ticket_status = $email->ticketStatus;
    	}
    	return new EmailResource($emailTemplate);
    }

    public function update(Request $request, $id)
    {
    	$emailTemplate = EmailTemplate::FindOrFail($id);

    	$emailTemplate->subject = $request->input('subject');
    	$emailTemplate->body = $request->input('body');
    	//$emailTemplate->edited_by = $request->input('edited_by');

    	if ($emailTemplate->save())
    		return new EmailResource($emailTemplate);
    }
}
