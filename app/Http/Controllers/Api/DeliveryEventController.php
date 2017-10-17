<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\Delivery;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeliveryEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Event $event
     * @return \Illuminate\Http\Response
     */
    public function index(Event $event)
    {
        if ($event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        return response()->json($event->deliveries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event $event
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Event $event)
    {
        $data = $this->validate(request(), [
            'address' => 'required|string',
            'deliver_on' => 'required|date',
            'description' => 'nullable|string',
            'fee' => 'nullable|numeric',
        ]);

        if ($event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $delivery = new Delivery();
        $delivery->address = $request->address;
        $delivery->deliver_on = Carbon::parse($request->deliver_on);
        $delivery->description = $request->description;
        $delivery->fee = $request->fee;
        $delivery->account()->associate(Auth::user()->account);
        $delivery->event()->associate($event);
        $delivery->save();

        return response()->json($delivery);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
