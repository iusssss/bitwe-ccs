<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Client;
use App\Http\Resources\Client as ClientResource;
use App\Company;
use DB;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all();

        return ClientResource::collection($clients);
    }

    public function fileUpload(Request $request) {
        $file =  $request->file('file')->getRealPath();
        $data = array_map('str_getcsv', file($file));
        return $data;
    }

    function fileSave(Request $request) {
        $clients = $request->input('clients');
        if (Client::insert($clients))
            return "success";
        return "error";
    } 

    public function byCompany($id) {
        $clients = Client::where('company_id', $id)->get();

        return ClientResource::collection($clients);
    }

    public function byPhone($phone) {
        $client = Client::where('contactNumber', $phone)->get();
        return new ClientResource($client[0]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $companies = Company::all();
        // return view('clients.create')->with('companies', $companies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:50'],
            // 'email' => ['string', 'email', 'max:255'],
            // 'phone_number' => ['regex:/(9)[0-9]{9}/','max:10']
        ]);

        $client = new Client;
        
        $client->fullname = $request->input('fullname');
        $client->email = $request->input('email');
        $client->contactNumber = $request->input('phone_number');
        $client->company_id = $request->input('company_id');

        if ($client->save())
            return new ClientResource($client);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::findOrFail($id);

        return new ClientResource($client);
    }

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
        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:50'],
            // 'email' => ['string', 'email', 'max:255'],
            // 'phone_number' => ['regex:/(9)[0-9]{9}/','max:10']
        ]);
        $client = Client::findOrFail($id);
        
        $client->fullname = $request->input('fullname');
        $client->email = $request->input('email');
        $client->contactNumber = $request->input('contactNumber');

        if ($client->save())
            return new ClientResource($client);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);

        if ($client->delete()) {
            return new ClientResource($client);
        }
    }
}
