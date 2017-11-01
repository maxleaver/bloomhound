<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Event;
use App\Setup;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

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
     * @param  \App\Event $event
     * @return \Illuminate\Http\Response
     */
    public function store(Event $event)
    {
        $data = request()->validate([
            'address' => 'required|string',
            'setup_on' => 'required|date',
            'description' => 'nullable|string',
            'fee' => 'nullable|numeric',
        ]);

        if ($event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $setup = new Setup();
        $setup->address = $data['address'];
        $setup->setup_on = Carbon::parse($data['setup_on']);
        $setup->description = $data['description'];
        $setup->fee = $data['fee'];
        $setup->account()->associate(Auth::user()->account);
        $setup->event()->associate($event);
        $setup->save();

        return response()->json($setup);
    }
}
