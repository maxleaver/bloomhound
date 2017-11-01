<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Controllers\Controller;

class AccountProfileController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'website' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        Auth::user()->account->update($data);
    }
}
