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
        return EndorsedTicket::all();
    } 
    public function store(Request $request)
    {
        $endorsed->fill($request->all());
        if ($endorsed->save())
            return $endorsed;
    }

    public function destroy($id)
    {
        $endorsed = EndorsedTicket::findOrFail($id);
        if ($endorsed->delete())
            return $endorsed;
    }
}
