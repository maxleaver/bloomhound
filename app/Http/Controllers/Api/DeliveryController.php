<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Delivery;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DeliveryController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(Delivery $delivery)
    {
        if ($delivery->account->id !== Auth::user()->account->id) {
            abort(403);
        }

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
