<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Customer;
use App\Event;
use App\EventStatus;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Auth::user()->account->events->load('status');
        return response()->json($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->validate([
            'date' => 'required|date',
            'customer' => 'required|string',
            'name' => 'required|string',
        ]);

        // Create a new customer
        $customer = new Customer;
        $customer->name = $data['customer'];
        $customer->account()->associate(Auth::user()->account);
        $customer->save();

        // Create event
        $event = new Event;
        $event->name = $data['name'];
        $event->date = Carbon::parse($data['date']);
        $event->account()->associate(Auth::user()->account);
        $event->status()->associate(EventStatus::whereName('draft')->first());
        $event->customer()->associate($customer);
        $event->save();

        return response()->json($event);
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
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Event $event)
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        if ($event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $event->update([
            'name' => $data['name'],
            'date' => Carbon::parse($data['date']),
        ]);

        return response()->json($event);
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
