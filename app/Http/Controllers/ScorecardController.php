<?php

namespace App\Http\Controllers;

use App\Scorecard;
use App\Http\Resources\Evaluation;
use App\ScorecardDetails;
use Illuminate\Http\Request;

class ScorecardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $evaluations = Scorecard::all();
        return Evaluation::collection($evaluations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $i = 0;
        // $j = 0;
        // return $request->input("criterias.$i.questions.$j.confidence");
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
            return "SUCCESS";
        }
        // $i = 1;
        // return $request->input("criterias.$i.name");

        // $criteria_names = [];
        // $criteria_percentages = [];
        // $questions = [];
        return count($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Scorecard  $scorecard
     * @return \Illuminate\Http\Response
     */
    public function show(Scorecard $scorecard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Scorecard  $scorecard
     * @return \Illuminate\Http\Response
     */
    public function edit(Scorecard $scorecard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Scorecard  $scorecard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Scorecard $scorecard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Scorecard  $scorecard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Scorecard $scorecard)
    {
        //
    }
}
