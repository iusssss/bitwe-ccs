<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helper\AppHelper as logger;

class AuthController extends Controller
{
    function login(Request $request) {
    	$http = new \GuzzleHttp\Client;

    	try {
    		$response = $http->post(config('services.passport.login_endpoint'), [
    			'form_params' => [
    				'grant_type' => 'password',
    				'client_id' => config('services.passport.client_id'),
    				'client_secret' => config('services.passport.client_secret'),
    				'username' => $request->username,
    				'password' => $request->password,
    			]
    		]);
    		return $response->getBody();
    	} catch(\GuzzleHttp\Exception\BadResponseException $e) {
    		if ($e->getCode() == 400) {
    			return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
    		} else if ($e->getCode() == 401) {
    			return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
    		}

    		return response()->json('Something went wrong on the server.', $e->getCode());
    	}
    }

    function register(Request $request) {
		$validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:5', 'max:25', 'unique:users'],
            'phone_number' => ['required','regex:/(9)[0-9]{9}/','max:10']
		]);
		$user = User::create([
			'name' => $validated['name'],
			'email' => $validated['email'],
			'password' => Hash::make($validated['password']),
			'worker_sid' => $request->worker_sid,
            'privilege' => $request->privilege,
            'contact_no' => '+63' . $validated['phone_number'],
		]);
        logger::createLog('User', 'Registered a user', auth()->user()->id);
        return $user;
    }

    function logout() {
        logger::createLog('User', 'Logged out', auth()->user()->id);
    	auth()->user()->tokens->each(function ($token, $key) {
    		$token->delete();
    	});
    	return response()->json('Logged out successfully', 200);
    }
}
