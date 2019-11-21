<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class CallRecordsController extends Controller
{
    function index() 
    {
    	define('accountSid', env("TWILIO_ACCOUNT_SID"));
    	define('authToken', env("TWILIO_AUTH_TOKEN"));
		$twilio = new Client(accountSid, authToken);

		$recordings = $twilio->recordings
                     ->read(array(), 20);
        $records = [];
		foreach ($recordings as $i => $record) {
            $r = new \stdClass;
			$r->id = $record->sid;
            $r->dateCreated = $record->dateCreated;
            $r->duration = $record->duration;
            $r->callTo = $twilio->calls($record->callSid)
               ->fetch()->to;
            $records[$i] = $r;
		}
		return $records;
    }

    function deleteRecord($recordSid) {
        define('accountSid', env("TWILIO_ACCOUNT_SID"));
        define('authToken', env("TWILIO_AUTH_TOKEN"));
        $twilio = new Client(accountSid, authToken);
        $twilio->recordings($recordSid)
       ->delete();
    }
    
    function getRecord($recordSid)
    {
    	define('accountSid', env("TWILIO_ACCOUNT_SID"));
    	define('authToken', env("TWILIO_AUTH_TOKEN"));
		$twilio = new Client(accountSid, authToken);

		$recording = $twilio->recordings($recordSid)
                    ->fetch();
        return $recording->subresourceUris;
    }
}