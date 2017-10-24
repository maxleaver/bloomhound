<?php

namespace App\Http\Controllers\Api;

use App\Delivery;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeliveryController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Delivery $delivery)
    {
        if ($delivery->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $data = $this->validate(request(), [
            'address' => 'required|string',
            'description' => 'nullable|string',
            'fee' => 'nullable|numeric',
            'deliver_on' => 'required|date',
        ]);

        $delivery->update([
            'address' => $request->address,
            'description' => $request->description,
            'fee' => $request->fee,
            'deliver_on' => Carbon::parse($request->deliver_on),
        ]);

        return response()->json($delivery->fresh());
    }
}
