<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Proposal;
use App\Discount;
use App\Http\Controllers\Controller;
use App\Rules\IsLessThanValue;
use Illuminate\Http\Request;

class DiscountProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function index(Proposal $proposal)
    {
        if ($proposal->event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        return response()->json($proposal->discounts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function store(Proposal $proposal)
    {
        if ($proposal->event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $data = request()->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed,percent|max:255',
            'amount' => 'required|numeric|min:1',
        ]);

        $discount = new Discount($data);
        $discount->discountable_id = $proposal->id;
        $discount->discountable_type = 'App\Proposal';
        $discount->save();

        return response()->json($discount);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Proposal  $proposal
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(Proposal $proposal, Discount $discount)
    {
        if ($proposal->event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        if ($discount->discountable_id !== $proposal->id) {
            abort(403);
        }

        $data = request()->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed,percent|max:255',
            'amount' => 'required|numeric|min:1',
        ]);

        $discount->update($data);

        return response()->json($discount);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Proposal  $proposal
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proposal $proposal, Discount $discount)
    {
        if ($proposal->event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        if ($discount->discountable_id !== $proposal->id) {
            abort(404);
        }

        $discount->delete();

        return response()->json();
    }
}
