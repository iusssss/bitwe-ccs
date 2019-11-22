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

    public function store(Request $request)
    {
        $ticketUpdate = new TicketUpdate();
        $ticketUpdate->fill($request->all());
        if ($ticketUpdate->save()) {
            $ticketUpdate = new TicketUpdateResource($ticketUpdate);
            return event(new TicketUpdateCreated($ticketUpdate));
        }
    }

    public function show($id)
    {
        $ticketUpdate = Ticket::findOrFail($id);

        return new TicketResource($ticketUpdate);
    }

    public function update(Request $request, TicketUpdates $ticketUpdates)
    {
        //
    }

    public function destroy(TicketUpdates $ticketUpdates)
    {
        //
    }
}
