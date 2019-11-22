<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Resources\Transaction as TransactionResource;
use App\Transaction;
use App\Ticket;
use App\TicketUpdate;
use App\TransactionSubject;
use App\Service;
use DB;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $lastTwoDays = date("Y-m-d", strtotime('-2 days') );
        // $updates = [];
        // $tickets = Ticket::with('updates')->get();
        // foreach ($tickets as $i => $ticket) {
        //     if ($ticket->updates[0]->created_at->format('Y-m-d') <= $lastTwoDays)
        //         $updates[$i] = $ticket->updates[0];
        // }
        // // $updates[1]->status = 3;
        // // $updates[1]->save();
        // return $updates;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $subjects = TransactionSubject::all();
        // $services = Service::all();
        // $objects = array(
        //     'subjects' => $subjects,
        //     'services' => $services
        // );
        // return view('transactions.create')->with($objects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $transaction = new Transaction;
        $transaction->timeElapsed = $request->input('timeElapsed');
        $transaction->startTime = $request->input('startTime');
        $transaction->touchPoint = $request->input('touchpoint');
        $transaction->issue = $request->input('issue');
        $transaction->resolution = $request->input('resolution');
        $transaction->user_id = $request->input('user_id');
        $transaction->client_id = $request->input('client_id');
        $transaction->service_id = $request->input('service_id');
        $transaction->subject_id = $request->input('subject_id');

        if ($transaction->save())
            return new TransactionResource($transaction);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);

        return new TransactionResource($transaction);
    }

    // public function search(Request $request) {
    //     $transaction = Transaction::where('client_id', 'like', '%' . $request->input('client_id') . '%' )
    //     ->orWhere('service_id', 'like', '%' . $request->input('service_id') . '%' )
    // }

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

        $transaction = Transaction::findOrFail($id);

        $transaction->timeElapsed = $request->input('timeElapsed');
        $transaction->touchPoint = $request->input('touchpoint');
        $transaction->issue = $request->input('issue');
        $transaction->resolution = $request->input('resolution');
        $transaction->service_id = $request->input('service_id');
        $transaction->subject_id = $request->input('subject_id');

        if ($transaction->save())
            return new TransactionResource($transaction);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->delete())
            return new TransactionResource($transaction);
    }
}
