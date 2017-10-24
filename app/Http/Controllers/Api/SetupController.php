<?php

namespace App\Http\Controllers\Api;

use App\Setup;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SetupController extends Controller
{
	/**
	 * Updates an event setup
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Setup  $setup
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Setup $setup)
	{
		if ($setup->account->id !== Auth::user()->account->id) {
			abort(403);
		}

		$data = $this->validate(request(), [
            'address' => 'required|string',
            'description' => 'nullable|string',
            'fee' => 'nullable|numeric',
            'setup_on' => 'required|date',
        ]);

        $setup->update([
            'address' => $request->address,
            'description' => $request->description,
            'fee' => $request->fee,
            'setup_on' => Carbon::parse($request->setup_on),
        ]);

		return response()->json($setup->fresh());
	}
}
