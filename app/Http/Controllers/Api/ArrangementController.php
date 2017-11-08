<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Arrangement;
use App\Proposal;
use App\Http\Controllers\Controller;

class ArrangementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function index(Proposal $proposal)
    {
        $this->proposalIsValid($proposal);

        return response()->json($proposal->arrangements);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function store(Proposal $proposal)
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        $this->proposalIsValid($proposal);

        $arrangement = new Arrangement($data);
        $arrangement->account()->associate(Auth::user()->account);
        $arrangement->proposal()->associate($proposal);
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
            'override_price' => 'nullable|boolean',
            'price' => 'required_if:override_price,true|numeric|min:0.01',
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

    protected function proposalIsValid(Proposal $proposal)
    {
        if ($proposal->event->account->id !== Auth::user()->account->id) {
            abort(403);
        }
    }
}
