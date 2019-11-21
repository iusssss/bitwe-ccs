<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\TwiML;
use Twilio\Jwt\TaskRouter\WorkspaceCapability;

class AdminController extends Controller
{
    public function index() {
    	define('accountSid', env("TWILIO_ACCOUNT_SID"));
    	define('authToken', env("TWILIO_AUTH_TOKEN"));
    	define('workspaceSid', env("TWILIO_WORKSPACE_SID"));

        $capability = new WorkspaceCapability(accountSid, authToken, workspaceSid);
        $capability->allow("https://taskrouter.twilio.com", "POST");
        $capability->allowFetchSubresources();
        $capability->allowUpdatesSubresources();
        $capability->allowDeleteSubresources();

        $workspaceToken = $capability->generateToken(28800);
        return response()->json($workspaceToken);
    }
}
