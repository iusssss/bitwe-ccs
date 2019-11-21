<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Resources\Subject as SubjectResource;
use App\TransactionSubject;
use App\Service;

class TransactionSubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = TransactionSubject::all();

        return SubjectResource::collection($subjects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $services = Service::all();
        // return view('transactionSubjects.create')->with('services', $services);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'issue' => 'required',
            'resolution' => 'required'
        ]);

        $transactionSubject = new TransactionSubject;
        $transactionSubject->issue = $request->input('issue');
        $transactionSubject->resolution = $request->input('resolution');
        $transactionSubject->service_id = $request->input('service_id');

        if ($transactionSubject->save())
            return new SubjectResource($transactionSubject);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subject = TransactionSubject::findOrFail($id);

        return new SubjectResource($subject);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'issue' => 'required',
            'resolution' => 'required'
        ]);

        $transactionSubject = TransactionSubject::findOrFail($id);
        $transactionSubject->issue = $request->input('issue');
        $transactionSubject->resolution = $request->input('resolution');
        $transactionSubject->service_id = $request->input('service_id');

        if ($transactionSubject->save())
            return new SubjectResource($transactionSubject);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subject = TransactionSubject::findOrFail($id);

        if ($subject->delete())
            return new SubjectResource($subject);
    }
}
