<?php

namespace App\Http\Controllers\Twilio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Twiml;
use App\Service;

class IncomingCallController extends Controller
{
    public function respondToUser()
    {
        $services = Service::all();
        $num = count($services);
        $response = new Twiml();
        $params = array();
        $params['action'] = '/api/call/enqueue';
        $params['numDigits'] = $num;
        $params['timeout'] = 60;
        $params['method'] = "POST";

        $params = $response->gather($params);
        $params->say(
            'Thank you for calling C  DECK. For A M S, press one. For E Trade, press two. For Go Fast, press three.'
        );

        return response($response)->header('Content-Type', 'text/xml');
    }
}
