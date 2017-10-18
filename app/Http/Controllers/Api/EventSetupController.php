<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\Setup;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventSetupController extends Controller
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

        return response()->json($event->setups);
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
            'setup_on' => 'required|date',
            'description' => 'nullable|string',
            'fee' => 'nullable|numeric',
        ]);

        if ($event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $setup = new Setup();
        $setup->address = $request->address;
        $setup->setup_on = Carbon::parse($request->setup_on);
        $setup->description = $request->description;
        $setup->fee = $request->fee;
        $setup->account()->associate(Auth::user()->account);
        $setup->event()->associate($event);
        $setup->save();

        return response()->json($setup);
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
