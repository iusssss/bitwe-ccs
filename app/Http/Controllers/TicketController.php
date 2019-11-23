<?php

namespace App\Http\Controllers;

use App\Events\TicketCreated;
use App\Exports\TicketsExport;
use App\Http\Requests;
use App\Http\Resources\Ticket as TicketResource;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use DB;
use Event;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TicketController extends Controller
{
    public function index() {
    	$tickets = Ticket::orderBy('created_at', 'desc')->paginate(15);
    	return TicketResource::collection($tickets);
    }

    public function export($filter)
    {
        // return Ticket::findOrFail(1911080013)->resolved[0]->created_at;
        return Excel::download(new TicketsExport($filter), 'tickets.xlsx');
    }

    public function ticketsByUser($user_id)
    {
        $tickets = Ticket::where('assignedTo', $user_id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
        return TicketResource::collection($tickets); 
    }

    public function ticketsThisYear() {
        $tickets = Ticket::whereYear('created_at', date('Y'))
                    ->orderBy('created_at', 'desc')->get();
        return TicketResource::collection($tickets);
    } 

    public function ticketsThisYearByUser($user_id) {
        $tickets = Ticket::where('assignedTo', $user_id)
                    ->whereYear('created_at', date('Y'))
                    ->orderBy('created_at', 'desc')->get();
        return TicketResource::collection($tickets);
    }

    public function ticketsPerMonth() {
        $tickets = DB::table('tickets as t')
                ->select(DB::raw("s.name,
                    DATE_FORMAT(t.created_at, '%b') as month,
                    COUNT(s.name) as total"
                ))
                ->join('services as s', 's.id', '=', 't.service_id')
                ->whereYear('t.created_at', date('Y'))
                ->groupBy(DB::raw("s.name, YEAR(t.created_at), MONTH(t.created_at)"))
                ->get();
        return $tickets;
    }

    public function ticketsPerDayInMonth() {
        $tickets = DB::table('tickets as t')
                ->select(DB::raw("s.name,
                    DAY(t.created_at) as day,
                    COUNT(s.name) as total"
                ))
                ->join('services as s', 's.id', '=', 't.service_id')
                ->whereYear('t.created_at', date('Y'))
                ->whereMonth('t.created_at', date('m'))
                ->groupBy(DB::raw("s.name, YEAR(t.created_at), DAY(t.created_at)"))
                ->get();
        return $tickets;
    }

    public function ticketsPerWeek() {
        Carbon::setWeekStartsAt(Carbon::MONDAY);
        Carbon::setWeekEndsAt(Carbon::SUNDAY);
        $tickets = DB::table('tickets as t')
                ->select(DB::raw("s.name,
                    DATE_FORMAT(t.created_at, '%a') as day,
                    COUNT(s.name) as total"
                ))
                ->join('services as s', 's.id', '=', 't.service_id')
                ->whereBetween('t.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->groupBy(DB::raw("s.name, YEAR(t.created_at), DAY(t.created_at)"))
                ->get();
        return $tickets;
    }

    public function ticketsThisDay() {
        $tickets = DB::table('tickets as t')
                ->select(DB::raw("s.name,
                    HOUR(t.created_at) as hour,
                    COUNT(s.name) as total"
                ))
                ->join('services as s', 's.id', '=', 't.service_id')
                ->whereYear('t.created_at', date('Y'))
                ->whereMonth('t.created_at', date('m'))
                ->whereDay('t.created_at', date('d'))
                ->groupBy(DB::raw("s.name, HOUR(t.created_at)"))
                ->get();
        return $tickets;
    }

    public function store(Request $request) {
    	$ticket = new Ticket;
    	$ticket->ticketId = $request->input('ticketId');
        $ticket->type = $request->input('type');
    	$ticket->assignedTo = auth()->user()->id;
        $ticket->startTime = $request->input('startTime');
        $ticket->touchPoint = $request->input('touchPoint');
        $ticket->issue = $request->input('issue');
        $ticket->client_id = $request->input('client_id');
        $ticket->service_id = $request->input('service_id');
        try {
            if ($ticket->save()) {
                $ticket = new TicketResource($ticket);
                return event(new TicketCreated($ticket));
            }
        } catch (\Exception $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return "duplicate";
            }
        }
    	// if ($ticket->save()) {
    	// 	$ticket = new TicketResource($ticket);
     //        return event(new TicketCreated($ticket));
     //    } else {
     //        return 'error';
     //    }
    }
    // public function test() {
    //     return auth()->user()->privilege;
    // }

    public function lastTicket() {
        $ticket = Ticket::orderBy('ticketId', 'desc')->first();
        if (!$ticket)
            return null;

        return new TicketResource($ticket);
    }

    public function show($id) {
    	$ticket = Ticket::find($id);

    	return new TicketResource($ticket);
    }

    public function update(Request $request, $id) {
    	$ticket = Ticket::findOrFail($id);
        if ($request->has('ticketId'))
            $request->request->remove('ticketId');
        $ticket->fill($request->all());
    	if ($ticket->save()) {
    		return new TicketResource($ticket);
    	}
    }

    public function destroy($id) {
    	$ticket = Ticket::where('ticketId', $id)->firstOrFail();
    	if ($ticket->delete()) {
    		return new TicketResource($ticket);
    	}
    }
}
