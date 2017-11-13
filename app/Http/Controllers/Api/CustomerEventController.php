<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Customer;
use App\Event;
use App\EventStatus;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CustomerEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('in_account:customer');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function index(Customer $customer)
    {
        return response()->json($customer->events->load('status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function store(Customer $customer)
    {
        $data = request()->validate([
            'date' => 'required|date|after:now',
            'name' => 'required|string',
        ]);

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
}
