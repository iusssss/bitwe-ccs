<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\SystemSetting;
use App\Helper\AppHelper as logger;

class SystemSettingsController extends Controller
{
	public function index() {
		return SystemSetting::all();
	}
    public function update(Request $request, $id) {
    	$setting = SystemSetting::findOrFail($id);
    	$setting->fill($request->all());
    	if ($setting->save()) {
            logger::createLog('Settings', 'Updated settings', auth()->user()->id);
    		return $setting;
    	}
    }
}
