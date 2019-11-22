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
    private $record_chance = 100;
    public function shouldRecord($chance)
    {
        $randomChance = rand(0, 100);
        if ($randomChance <= $chance)
            return true;
        return false;
    }
    public function assignTask()
    {
        define('post_work_activity_sid', env("TWILIO_POST_WORK_ACTIVITY_SID"));
        
        $dequeueInstructionModel = new \stdClass;
        $dequeueInstructionModel->instruction = "dequeue";
        $dequeueInstructionModel->post_work_activity_sid = post_work_activity_sid;
        if ($this->shouldRecord($this->record_chance))
            $dequeueInstructionModel->record = "record-from-answer";

        $dequeueInstructionJson = json_encode($dequeueInstructionModel);

        return response($dequeueInstructionJson)
            ->header('Content-Type', 'application/json');
    }
}
