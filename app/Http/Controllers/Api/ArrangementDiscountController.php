<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Arrangement;
use App\Discount;
use App\Rules\IsLessThanValue;
use App\Http\Controllers\Controller;

class ArrangementDiscountController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Arrangement  $arrangement
     * @return \Illuminate\Http\Response
     */
    public function store(Arrangement $arrangement)
    {
        if ($arrangement->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $data = request()->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed,percent|max:255',
            'amount' => [
                'required',
                'numeric',
                'min:1',
                new IsLessThanValue('arrangement', $arrangement->total_price),
            ],
        ]);

        $discount = new Discount($data);
        $discount->discountable_id = $arrangement->id;
        $discount->discountable_type = 'App\Arrangement';
        $discount->account()->associate(Auth::user()->account);
        $discount->save();

        return response()->json($discount->load('discountable'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Arrangement $arrangement
     * @param  \App\Discount    $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Arrangement $arrangement, Discount $discount)
    {
        if ($arrangement->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $discount->delete();

        return response()->json($arrangement->fresh()->load('discounts'));
    }
}
