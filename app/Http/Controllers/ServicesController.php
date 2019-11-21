<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Resources\Service as ServiceResource;
use App\Service;
use Illuminate\Http\Request;
use App\Exports\ServicesExport;
use Maatwebsite\Excel\Facades\Excel;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::all();

        return ServiceResource::collection($services);
    }

    public function export()
    {
        return Excel::download(new ServicesExport, 'services.xlsx');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $service = new Service;

        $service->name = $request->input('name');
        $service->description = $request->input('description');
        if ($service->save())
            return new ServiceResource($service);
    }

    public function show($id)
    {
        $service = Service::findOrFail($id);

        return new ServiceResource($service);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $service = Service::findOrFail($id);

        $service->name = $request->input('name');
        $service->description = $request->input('description');
        
        if ($service->save())
            return new ServiceResource($service);
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        if ($service->delete())
            return new ServiceResource($service);
    }
}
