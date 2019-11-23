<?php

namespace App\Http\Controllers;

use App\Scorecard;
use App\Http\Resources\Evaluation;
use App\ScorecardDetails;
use Illuminate\Http\Request;
use App\Helper\AppHelper as logger;

class ScorecardController extends Controller
{
    public function index()
    {
        $evaluations = Scorecard::orderBy('created_at', 'DESC')->paginate(10);
        return Evaluation::collection($evaluations);
    }

    public function byUser($user_id)
    {
        $evaluations = Scorecard::where('agent_id', $user_id)
                    ->orderBy('created_at', 'DESC')
                    ->paginate(10);
        return Evaluation::collection($evaluations);
    }

    public function store(Request $request)
    {
        $scorecard = new Scorecard();
        $scorecard->agent_id = $request->input("agent_id");
        $scorecard->admin_id = auth()->user()->id;
        $scorecard->score = $request->input("score");
        if ($scorecard->save()) {
            for ($i = 0; $i < count($request->input("criterias")); $i++) { 
                for ($j = 0; $j < count($request->input("criterias.$i.questions")); $j++) {
                    $details = new ScorecardDetails();
                    $details->scorecard_id = $scorecard->id;
                    $details->criteria_name = $request->input("criterias.$i.name");
                    $details->criteria_percentage = $request->input("criterias.$i.percentage");
                    $details->question = $request->input("criterias.$i.questions.$j.question");
                    $details->comment = $request->input("criterias.$i.questions.$j.comment");
                    $details->answer = $request->input("criterias.$i.questions.$j.answer");
                    $details->confidence = $request->input("criterias.$i.questions.$j.confidence");
                    $details->save();
                } 
            }
            logger::createLog('Scorecard', 'Evaluated a call recording of an agent', auth()->user()->id);
            return "SUCCESS";
        }

        return count($request->all());
    }

    public function destroy(Scorecard $scorecard)
    {
        //
    }
}
