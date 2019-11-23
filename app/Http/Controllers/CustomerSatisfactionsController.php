<?php

namespace App\Http\Controllers;

use App\CustomerSatisfaction;
use Illuminate\Http\Request;
use \Carbon\Carbon;

class CustomerSatisfactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        // Carbon::setWeekStartsAt(Carbon::SUNDAY);
        // Carbon::setWeekEndsAt(Carbon::SATURDAY);
        //Data::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        $csats = CustomerSatisfaction::where('user_id', $id)
                ->whereYear('created_at', date('Y'))
                ->get();
        return $csats;
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
        $csat = new CustomerSatisfaction();
        $csat->fill($request->all());
        if ($csat->save())
            $csat;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerSatisfaction  $customerSatisfaction
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerSatisfaction $customerSatisfaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerSatisfaction  $customerSatisfaction
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerSatisfaction $customerSatisfaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerSatisfaction  $customerSatisfaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerSatisfaction $customerSatisfaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerSatisfaction  $customerSatisfaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerSatisfaction $customerSatisfaction)
    {
        //
    }
}
