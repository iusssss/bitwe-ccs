<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\SystemSetting;

class SystemSettingsController extends Controller
{
	public function index() {
		return SystemSetting::all();
	}
    public function update(Request $request, $id) {
    	$setting = SystemSetting::findOrFail($id);
    	$setting->fill($request->all());
    	if ($setting->save()) {
    		return $setting;
    	}
    }
}
