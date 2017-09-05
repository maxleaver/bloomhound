<?php

namespace App\Http\Controllers;

use App\Invite;
use App\Mail\UserInvited;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class InviteController extends Controller
{
	public function store()
	{
		$data = $this->validate(request(), [
            'email' => 'required|string|email|max:255|unique:users'
        ]);

        // Create the account
        $invite = Invite::create([
            'email' => $data['email']
        ]);

        // Send the email
    	Mail::to($invite->email)->send(new UserInvited($invite));
	}

	// public function accept()
	// {
	// 	// Look up the invite
	//     if (!$invite = Invite::where('token', $token)->first()) {
	//         // If the invite doesn't exist do something more graceful than this
	//         abort(404);
	//     }

	//     // create the user with the details from the invite
	//     User::create(['email' => $invite->email]);

	//     // delete the invite so it can't be used again
	//     $invite->delete();

	//     // Return an authentication token here
	//     return 'some token';
	// }
}
