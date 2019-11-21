<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\TwiML;
use Twilio\Jwt\TaskRouter\WorkerCapability;

class WorkersController extends Controller
{
    //
    public function index($workerSid) {
    	define('accountSid', env("TWILIO_ACCOUNT_SID"));
    	define('authToken', env("TWILIO_AUTH_TOKEN"));
    	define('workspaceSid', env("TWILIO_WORKSPACE_SID"));
        $capability = new WorkerCapability(accountSid, authToken, workspaceSid, $workerSid);
        $capability->allowFetchSubresources();
        $capability->allowActivityUpdates();
        $capability->allowReservationUpdates();

        $workspaceToken = $capability->generateToken(28800);
        return response()->json($workspaceToken);
    }
    public function call() {
        define('accountSid', env("TWILIO_ACCOUNT_SID"));
        define('authToken', env("TWILIO_AUTH_TOKEN"));
        define('workspaceSid', env("TWILIO_WORKSPACE_SID"));

        $client = new Client(accountSid, authToken);
        $to = "+639393721614";
        $from = "+6322993771";
        $client->account->calls->create(
            $to,
            $from,
            array(
                "url" => "http://demo.twilio.com/docs/voice.xml"
            )
        );
    }
}
