<?php

namespace App\Http\Controllers;

use App\TicketUpdate;
use Illuminate\Http\Request;
use App\Http\Resources\TicketUpdate as TicketUpdateResource;
use App\Events\TicketUpdateCreated;

class TicketUpdatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ticketUpdates = TicketUpdate::all();
        return TicketUpdateResource::collection($ticketUpdates);
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
        $ticketUpdate = new TicketUpdate();
        $ticketUpdate->fill($request->all());
        if ($ticketUpdate->save()) {
            $ticketUpdate = new TicketUpdateResource($ticketUpdate);
            return event(new TicketUpdateCreated($ticketUpdate));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TicketUpdates  $ticketUpdates
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticketUpdate = Ticket::findOrFail($id);

        return new TicketResource($ticketUpdate);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TicketUpdates  $ticketUpdates
     * @return \Illuminate\Http\Response
     */
    public function edit(TicketUpdates $ticketUpdates)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TicketUpdates  $ticketUpdates
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TicketUpdates $ticketUpdates)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TicketUpdates  $ticketUpdates
     * @return \Illuminate\Http\Response
     */
    public function destroy(TicketUpdates $ticketUpdates)
    {
        //
    }
}
