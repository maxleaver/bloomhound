<?php

namespace App\Http\Controllers\Api;

use Auth;
use Validator;
use App\Proposal;
use App\Vendor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProposalVendorController extends Controller
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

        return response()->json($proposal->vendors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Proposal $proposal)
    {
        $rules = [
            'vendor_id' => [
                'nullable',
                'integer',
                'required_without:vendor_name',
                Rule::unique('proposal_vendor')->where(function ($query) use ($proposal) {
                    return $query->where('proposal_id', $proposal->id);
                }),
            ],
            'vendor_name' => 'nullable|string|required_without:vendor_id|max:255',
        ];

        $messages = [
            'vendor_id.unique' => 'That vendor is already part of this proposal',
        ];

        $data = Validator::make($request->all(), $rules, $messages)->validate();

        if ($proposal->event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        if (isset($request->vendor_id)) {
            // Find an existing vendor
            $vendor = Vendor::find($request->vendor_id);

            if ($vendor->account->id !== Auth::user()->account->id) {
                abort(403);
            }

            $proposal->vendors()->attach($vendor);

            return response()->json($vendor);
        } else {
            // Make a new vendor
            $vendor = new Vendor();
            $vendor->name = $request->vendor_name;
            $vendor->account()->associate(Auth::user()->account);
            $vendor->save();

            $proposal->vendors()->attach($vendor);
        }

        return response()->json($vendor);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Proposal  $proposal
     * @param  \App\Vendor $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proposal $proposal, Vendor $vendor)
    {
        if ($proposal->event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        if (!$vendor->proposals->contains($proposal)) {
            abort(404);
        }

        $proposal->vendors()->detach($vendor);
    }
}
