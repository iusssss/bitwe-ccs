<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Mail;
use DB;

class PasswordController extends Controller
{
    function sendResetLinkEmail(Request $request)
    {
    	$email = $request->input('email');
    	$user = User::where('email', $email)->first();
    	if ($user) {
    		$token = Str::random(61);
    		DB::table('password_resets')->insert(
    			['email' => $user->email, 'token' => $token]
    		);
    		$this->sendMail($user->email, $user->name, $token);
    		return $user->email;
    	}
    	else {
    		return null;
    	}
    }
    function reset(Request $request) {
    	$token = $request->input('token');
    	$user = $this->validateToken($token);
    	$user->password = Hash::make($request->input('password'));
    	if ($user->save()) {
    		DB::table('password_resets')->where('token', $token)->delete();
    		return 'success';
    	}
    }
    function validateToken($token) {
    	$reset = DB::table('password_resets')->where('token', $token)->first();
    	if ($reset) {
    		$user = User::where('email', $reset->email)->first();
    		unset($user->password);
    		unset($user->privilege);
    		unset($user->worker_sid);
    		unset($user->contact_no);
    		unset($user->name);
    		return $user;
    	}
    	else
    		return null;
    }
    function sendMail($email, $name, $token) {
    	$data = array(
    		'email' => $email,
    		'token' => $token,
    		'name' => $name,
		);
    	Mail::send('passwords.email', $data, function($message) use ($email) {
    		$message->to($email)
    				->subject("(CRM) Password Reset");
    		$message->from('test@gmail.com', 'CDEC CS');
    	});
    }
}
