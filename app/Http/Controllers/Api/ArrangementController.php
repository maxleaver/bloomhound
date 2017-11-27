<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Arrangement;
use App\Proposal;
use App\Http\Controllers\Controller;

class ArrangementController extends Controller
{
    public function __construct()
    {
        $this->middleware('in_account:proposal.event')
            ->only(['index', 'store']);

        $this->middleware('in_account:arrangement')
            ->only(['show', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function index(Proposal $proposal)
    {
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
        $arrangement->delete();
    }
}
