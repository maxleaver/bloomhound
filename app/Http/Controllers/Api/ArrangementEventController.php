<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Arrangement;
use App\Event;
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
     * @return \Illuminate\Http\Response
     */
    public function store(Event $event)
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        $this->eventIsValid($event);

        $arrangement = new Arrangement($data);
        $arrangement->account()->associate(Auth::user()->account);
        $arrangement->event()->associate($event);
        $arrangement->save();

        return response()->json($arrangement);
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
     * @param  \App\Arrangement  $arrangement
     * @return \Illuminate\Http\Response
     */
    public function update(Arrangement $arrangement)
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($arrangement->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $arrangement->update($data);

        return response()->json($arrangement->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Arrangement  $arrangement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Arrangement $arrangement)
    {
        if ($arrangement->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $arrangement->delete();
    }

    protected function eventIsValid(Event $event)
    {
        if ($event->account->id !== Auth::user()->account->id) {
            abort(403);
        }
    }
}
