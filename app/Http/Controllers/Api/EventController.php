<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Customer;
use App\Event;
use App\EventStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        return response()->jsend_success($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate(request(), [
            'date' => 'required|date',
            'name' => 'required|string',
        ]);

        $event = new Event;
        $event->name = $data['name'];
        $event->date = Carbon::parse($data['date']);
        $event->account()->associate(Auth::user()->account);
        $event->status()->associate(EventStatus::whereName('draft')->first());
        $event->save();

        return response()->jsend_success($event);
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
