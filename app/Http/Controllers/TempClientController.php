<?php

namespace App\Http\Controllers;

use App\TempClient;
use Illuminate\Http\Request;
use App\Http\Resources\Ticket as TicketResource;

class TempClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function unregistered() 
    {
        $clients = TempClient::where('registered', false)->get();
        foreach ($clients as $client) {
            $client->ticket = $client->ticket;
        }
        return $clients;
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
        $client = new TempClient();
        $client->fill($request->all());
        if ($client->save())
            return $client;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TempClient  $tempClient
     * @return \Illuminate\Http\Response
     */
    public function show($ticketId)
    {
        $client = TempClient::where('ticket_id', $ticketId)->get();
        return $client;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TempClient  $tempClient
     * @return \Illuminate\Http\Response
     */
    public function edit(TempClient $tempClient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TempClient  $tempClient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $client = new TempClient();
        $client->fill($request->all());
        if ($client->save())
            return $client;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TempClient  $tempClient
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = TempClient::findOrFail($id);
        if ($client->delete())
            return $client;
    }
}
