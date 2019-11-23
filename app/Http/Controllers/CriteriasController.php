<?php

namespace App\Http\Controllers;

use App\Criteria;
use App\Question;
use Illuminate\Http\Request;
use App\Helper\AppHelper as logger;

class CriteriasController extends Controller
{
    public function index()
    {
        $criterias = Criteria::all();
        foreach ($criterias as $criteria) {
            $criteria->questions = $criteria->questions;
        }
        return $criterias;
    } 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'percentage' => 'required|regex:/^[0-9]+$/'
        ]);
        $criteria = new Criteria();
        $criteria->fill($request->all());
        if ($criteria->save()) {
            logger::createLog('Criteria', 'Added a new criteria', auth()->user()->id);
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
            logger::createLog('Question', 'Added a new question to a criteria', auth()->user()->id);
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
            logger::createLog('Question', 'Updated a question from a criteria', auth()->user()->id);
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
        if ($question->delete()) {
            logger::createLog('Question', 'Deleted a question from a criteria', auth()->user()->id);
            return $question;
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'percentage' => 'required|regex:/^[0-9]+$/'
        ]);
        $criteria = Criteria::findOrFail($id);
        $criteria->fill($request->all());
        if ($criteria->save()) {
            logger::createLog('Criteria', 'Updated a criteria', auth()->user()->id);
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
        if ($criteria->delete()) {
            logger::createLog('Criteria', 'Deleted a criteria', auth()->user()->id);
            return $criteria;
        }
    }
}
