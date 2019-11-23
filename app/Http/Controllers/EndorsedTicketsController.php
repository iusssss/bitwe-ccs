<?php

namespace App\Http\Controllers;

use App\EndorsedTicket;
use Illuminate\Http\Request;

class EndorsedTicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $endorsed = EndorsedTicket::all();
        foreach ($endorsed as $e) {
            $e->user = $e->user;
            unset($e->user->email);
            unset($e->user->password);
            unset($e->user->privilege);
            unset($e->user->worker_sid);
            unset($e->user->contact_no);
        }
        return $endorsed;
    } 
    public function store(Request $request)
    {
        $ticket_id = $request->input('ticket_id');
        $endorsed = EndorsedTicket::where('ticket_id', $ticket_id)->get();
        if (count($endorsed) > 0)
            return 'error';
        $endorsed = new EndorsedTicket();
        $endorsed->fill($request->all());
        if ($endorsed->save()) {
            $endorsed->user = $endorsed->user;
            return $endorsed;
        }
    }

    public function destroy($id)
    {
        $endorsed = EndorsedTicket::findOrFail($id);
        if ($endorsed->delete())
            return $endorsed;
    }
}
