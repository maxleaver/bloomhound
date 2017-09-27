<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Arrangement;
use App\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArrangementEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function index(Event $event)
    {
        $this->eventIsValid($event);

        return response()->json($event->arrangements);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \App\Event  $event
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Event $event, Request $request)
    {
        $data = $this->validate(request(), [
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
        ]);

        $this->eventIsValid($event);

        $arrangement = new Arrangement();
        $arrangement->name = $request['name'];
        $arrangement->account()->associate(Auth::user()->account);
        $arrangement->save();

        $arrangement->events()->save($event, ['quantity' => $request['quantity']]);

        // Find the arrangement via the event, so it includes the quantity
        return response()->json($event->arrangements()->find($arrangement->id));
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

    protected function eventIsValid(Event $event)
    {
        if ($event->account->id !== Auth::user()->account->id) {
            abort(403);
        }
    }
}
