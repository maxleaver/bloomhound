<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Controllers\Controller;

class AccountSettingController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $data = request()->validate([
            'use_tax' => 'required|boolean',
            'tax_amount' => 'nullable|numeric|min:0.01',
        ]);

        Auth::user()->account->settings->update($data);
    }
}
