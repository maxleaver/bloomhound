<?php

namespace App\Http\Controllers;

use Auth;
use App\Account;
use App\Invite;
use App\Mail\UserInvited;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class InviteController extends Controller
{
	public function index()
	{
		// Get list of invitations for authenticated users account
		$invites = Invite::where('account_id', Auth::user()->account->id)->get();
		return json_encode(compact('invites'));
	}

	public function store()
	{
		$data = $this->validate(request(), [
            'email' => 'required|string|email|max:255|unique:users'
        ]);

		// Find the authenticated users account
        $account = Account::find(Auth::user()->account->id);

        // Create the invitation
        $invite = $account->invitations()->create([
        	'email' => $data['email']
        ]);

        // Send the email
    	Mail::to($invite->email)->send(new UserInvited($invite));
	}

	public function accept(Invite $invite)
	{
		$data = $this->validate(request(), [
			'name' => 'required|string|max:255',
			'password' => 'required|string|min:6'
		]);

		// Add new user to account
		$user = $invite->account->users()->create([
			'email' => $invite->email,
			'name' => $data['name'],
			'password' => bcrypt($data['password']),
		]);

		$invite->delete();

		$token = JWTAuth::fromUser($user);

		// Return auth token
		return json_encode(compact('token'));
	}
}
