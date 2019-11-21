<?php

namespace App\Http\Controllers\Twilio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Twiml;
use App\Service;
use Twilio\Rest\Client;

class EnqueueCallController extends Controller
{
    public function enqueueCall(Request $request)
    {
    	define('workflowSid', env('TWILIO_WORKFLOW_SID'));
    	
        $selectedSkillInstruction = new \StdClass();
        $selectedSkillInstruction->selected_skill = $this->_getSelectedSkill($request);
        $response = new Twiml();
        $enqueue = $response->enqueue(['workflowSid' => workflowSid]);
        $enqueue->task(json_encode($selectedSkillInstruction));
        return response($response)->header('Content-Type', 'text/xml');
    }
    /**
     * Gets the wanted product upon the user's input
     *
     * @param $request Request of the user
     *
     * @return string selected product: "ProgrammableSMS" or "ProgrammableVoice"
     */
    private function _getSelectedSkill($request)
    {
        $input = $request->input("Digits");
        $services = Service::all();
        $skills = [];
        foreach ($services as $service) {
            $skills[] = $service;
        }
        if ($input > count($services) || $input == 0)
            return "1==1";
        $gathered = $skills[$input - 1]->name;
        return $gathered;
    }

    public function createTask($id) {
        $request = new Request();
        $request->replace(['Digits' => $id]);
        $skill = $this->_getSelectedSkill($request);
        define('accountSid', env('TWILIO_ACCOUNT_SID'));
        define('authToken', env('TWILIO_AUTH_TOKEN'));
        define('workspaceSid', env('TWILIO_WORKSPACE_SID'));
        define('workflowSid', env('TWILIO_WORKFLOW_SID'));

        $client = new Client(accountSid, authToken);
        $task = $client->taskrouter
        ->workspaces(workspaceSid)
        ->tasks
        ->create(array(
            'attributes' => '{"selected_skill":"' . $skill . '","from":"+639393721614"}',
            'workflowSid' => workflowSid
        ));

        echo "Task Created";
    }
}
