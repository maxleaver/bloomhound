<?php

namespace App\Http\Controllers\Api;

use App\Delivery;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DeliveryController extends Controller
{
    public function __construct()
    {
        $this->middleware('in_account:delivery');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(Delivery $delivery)
    {
        $data = request()->validate([
            'address' => 'required|string',
            'description' => 'nullable|string',
            'fee' => 'nullable|numeric',
            'deliver_on' => 'required|date',
        ]);

        $delivery->update([
            'address' => $data['address'],
            'description' => $data['description'],
            'fee' => $data['fee'],
            'deliver_on' => Carbon::parse($data['deliver_on']),
        ]);

        return response()->json($delivery->fresh());
    }
}
