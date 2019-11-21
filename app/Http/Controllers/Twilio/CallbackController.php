<?php

namespace App\Http\Controllers\Twilio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\TaskRouterException;
use App\MissedCall;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class CallbackController extends Controller
{
    public function assignTask()
    {
        define('post_work_activity_sid', env("TWILIO_POST_WORK_ACTIVITY_SID"));
        
        $dequeueInstructionModel = new \stdClass;
        $dequeueInstructionModel->instruction = "dequeue";
        $dequeueInstructionModel->post_work_activity_sid = post_work_activity_sid;
        $dequeueInstructionModel->record = "record-from-answer";

        $dequeueInstructionJson = json_encode($dequeueInstructionModel);

        return response($dequeueInstructionJson)
            ->header('Content-Type', 'application/json');
    }
}