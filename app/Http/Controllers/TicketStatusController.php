<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TicketStatus;
use App\Http\Resources\TicketStatus as StatusResource;

class TicketStatusController extends Controller
{
    public function index() {
    	$status = TicketStatus::all();

    	return StatusResource::collection($status);
    }
}
