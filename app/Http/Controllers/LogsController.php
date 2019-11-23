<?php

namespace App\Http\Controllers;

use App\Log;
use Illuminate\Http\Request;
use App\Http\Resources\Log as LogResource;

class LogsController extends Controller
{
    public function index()
    {
        $logs = Log::orderBy('created_at', 'DESC')->paginate(10);
        return LogResource::collection($logs);
    }

    public function store(Request $request)
    {
        $log = new Log();
        $log->fill($request->all());
        if ($log->save()) {
            return $log;
        }
    }
}
