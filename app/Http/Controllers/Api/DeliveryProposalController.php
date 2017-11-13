<?php

namespace App\Http\Controllers\Api;

use Auth;
use DB;
use App\Arrangement;
use App\Delivery;
use App\Proposal;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DeliveryProposalController extends Controller
{
    public function __construct()
    {
        $this->middleware('in_account:proposal.event');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Proposal $proposal
     * @return \Illuminate\Http\Response
     */
    public function index(Proposal $proposal)
    {
        return response()->json($proposal->deliveries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proposal $proposal
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Proposal $proposal)
    {
        Validator::make($request->all(), [
            'address' => 'required|string',
            'deliver_on' => 'required|date',
            'description' => 'nullable|string',
            'fee' => 'nullable|numeric',
            'arrangements.*' => [
                'integer',
                Rule::exists('arrangements', 'id')->where(function ($query) use ($proposal) {
                    $query->where('proposal_id', $proposal->id);
                }),
            ],
        ])->validate();

        $delivery = DB::transaction(function () use ($request, $proposal) {
            $delivery = new Delivery();
            $delivery->address = $request->address;
            $delivery->deliver_on = Carbon::parse($request->deliver_on);
            $delivery->description = $request->description;
            $delivery->fee = $request->fee;
            $delivery->account()->associate(Auth::user()->account);
            $delivery->proposal()->associate($proposal);
            $delivery->save();

            if ($request->arrangements) {
                $arrangements = Arrangement::whereIn('id', $request->arrangements)->get();
                $delivery->arrangements()->saveMany($arrangements);
            }

            return $delivery;
        });

        return response()->json($delivery->load('arrangements'));
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
