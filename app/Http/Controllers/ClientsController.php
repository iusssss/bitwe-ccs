<?php

namespace App\Http\Controllers;

use App\Client;
use App\Company;
use App\Http\Requests;
use App\Http\Resources\Client as ClientResource;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientsExport;
use App\Helper\AppHelper as logger;

class ClientsController extends Controller
{
    public function index()
    {
        $clients = Client::all();

        return ClientResource::collection($clients);
    }

    public function export($company_id)
    {
        $companyName = Company::findOrFail($company_id)->name;
        return Excel::download(new ClientsExport($company_id), "clients of ".$companyName.".xlsx");
    }

    public function fileUpload(Request $request) {
        $file =  $request->file('file')->getRealPath();
        $data = array_map('str_getcsv', file($file));
        return $data;
    }

    function fileSave(Request $request) {
        $clients = $request->input('clients');
        if (Client::insert($clients)) {
            logger::createLog('Client', 'File uploaded to add new clients', auth()->user()->id);
            return "success";
        }
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

        if ($client->save()) {
            logger::createLog('Client', 'Added new client', auth()->user()->id);
            return new ClientResource($client);
        }
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);

        return new ClientResource($client);
    }

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

        if ($client->save()) {
            logger::createLog('Client', 'Updated a client', auth()->user()->id);
            return new ClientResource($client);
        }
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);

        if ($client->delete()) {
            logger::createLog('Client', 'Deleted new client', auth()->user()->id);
            return new ClientResource($client);
        }
    }
}
