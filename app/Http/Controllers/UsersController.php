<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Helper\AppHelper as logger;

class UsersController extends Controller
{
    public function index() 
    {
    	$users = User::paginate(10);

    	return UserResource::collection($users);
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function all() {
        $users = User::all();

        return UserResource::collection($users);
    }

    public function store(Request $request)
    {
    	$validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'name' => ['required', 'string', 'max:50', 'unique:users'],
            'contact_no' => 'required|regex:/(9)[0-9]{9}/'
        ]);
    	$user = new User;

    	$user->name = $validated['name'];
    	$user->email = $validated['email'];
        $user->worker_sid = $request->input('worker_sid');

    	if ($user->save())
    		return new UserResource($user);
    }

	public function show($id)
    {
    	$user = User::findOrFail($id);

    	return new UserResource($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::FindOrFail($id);
    	$validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
    		'email' => 'required|string|max:255|email|unique:users,email,'.$user->id,
            'phone' => ['required','regex:/(9)[0-9]{9}/','max:10']
    	]);
    	$user->name = $validated['name'];
    	$user->email = $validated['email'];
        $user->worker_sid = $request->input('worker_sid');
        $user->privilege = $request->input('privilege');
        $user->contact_no = '+63' . $validated['phone'];

    	if ($user->save()) {
            logger::createLog('User', 'Updated a user', auth()->user()->id);
    		return new UserResource($user);
        }
    }

    public function changePassword(Request $request, $id) {
        $user = User::findOrFail($id);
        $user->password = Hash::make($request->input('password'));

        if ($user->save())
            logger::createLog('User', 'Changed account password', auth()->user()->id);
            return new UserResource($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->delete()) {
            logger::createLog('User', 'Deleted a user', auth()->user()->id);
            return new UserResource($user);
        }
    }

    public function checkCurrentPassword(Request $request) {
        $currentPassword = $request->input('current_password');
        if (Hash::check($currentPassword, auth()->user()->password)) {
            return 'true';
        }
        return 'false';
    }
}
