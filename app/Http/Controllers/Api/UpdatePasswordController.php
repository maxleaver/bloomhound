<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Controllers\Controller;
use App\Rules\MatchesPassword;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $data = request()->validate([
            'current_password' => ['required', 'string', new MatchesPassword],
            'password' => 'required|string|min:6|confirmed',
        ]);

        Auth::user()->update(['password' => Hash::make($data['password'])]);
    }
}
