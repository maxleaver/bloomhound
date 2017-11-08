<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Event;
use App\Proposal;
use App\Http\Controllers\Controller;
use App\Http\Resources\Proposal as ProposalResource;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Event $event)
    {
        return response()->json($event->proposals);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Event $event)
    {
        if ($event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $event->active_proposal->duplicate();

        return response()->json($event->fresh()->active_proposal);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function show(Proposal $proposal)
    {
        if ($proposal->event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        return new ProposalResource($proposal);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Event  $event
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function update(Event $event, Proposal $proposal)
    {
        if ($proposal->event->id !== $event->id) {
            abort(403);
        }

        if ($event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $event->setActiveProposal($proposal);

        return response()->json($proposal);
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
