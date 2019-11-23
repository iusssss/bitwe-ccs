<?php

namespace App\Http\Controllers;

use App\CustomerSatisfaction;
use Illuminate\Http\Request;
use \Carbon\Carbon;

class CustomerSatisfactionsController extends Controller
{
    public function index($id)
    {
        $csats = CustomerSatisfaction::where('user_id', $id)
                ->whereYear('created_at', date('Y'))
                ->get();
        return $csats;
    }

    public function store(Request $request)
    {
        $csat = new CustomerSatisfaction();
        $csat->fill($request->all());
        if ($csat->save())
            $csat;
    }
}
