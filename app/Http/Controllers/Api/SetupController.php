<?php

namespace App\Http\Controllers\Api;

use App\Setup;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class SetupController extends Controller
{
    public function __construct()
    {
        $this->middleware('in_account:setup');
    }

    /**
     * Updates an event setup
     *
     * @param  \App\Setup  $setup
     * @return \Illuminate\Http\Response
     */
    public function update(Setup $setup)
    {
        $data = request()->validate([
            'address' => 'required|string',
            'description' => 'nullable|string',
            'fee' => 'nullable|numeric',
            'setup_on' => 'required|date',
        ]);

        $setup->update([
            'address' => $data['address'],
            'description' => $data['description'],
            'fee' => $data['fee'],
            'setup_on' => Carbon::parse($data['setup_on']),
        ]);

        return response()->json($setup->fresh());
    }
}
