<?php

namespace App\Http\Controllers;

use App\Criteria;
use App\Question;
use Illuminate\Http\Request;

class CriteriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $criterias = Criteria::all();
        foreach ($criterias as $criteria) {
            $criteria->questions = $criteria->questions;
        }
        return $criterias;
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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'percentage' => 'required|regex:/^[0-9]+$/'
        ]);
        $criteria = new Criteria();
        $criteria->fill($request->all());
        if ($criteria->save()) {
            return $criteria;
        }
    }

    public function storeQuestion(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:200'],
        ]);
        $question = new Question();
        $question->fill($request->all());
        if ($question->save()) {
            return $question;
        }
    }

    public function updateQuestion(Request $request, $id)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:200'],
        ]);
        $question = Question::findOrFail($id);
        $question->fill($request->all());
        if ($question->save()) {
            return $question;
        }
    }

    public function showQuestions($id)
    {
        $questions = Question::where('criteria_id', $id)->get();

        return $questions;
    }

    public function destroyQuestion($id)
    {
        $question = Question::findOrFail($id);
        if ($question->delete())
            return $question;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function show(Criteria $criteria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'percentage' => 'required|regex:/^[0-9]+$/'
        ]);
        $criteria = Criteria::findOrFail($id);
        $criteria->fill($request->all());
        if ($criteria->save()) {
            return $criteria;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $criteria = Criteria::findOrFail($id);
        if ($criteria->delete())
            return $criteria;
    }
}
