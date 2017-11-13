<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Proposal;
use App\Setup;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ProposalSetupController extends Controller
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
        return response()->json($proposal->setups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Proposal $proposal
     * @return \Illuminate\Http\Response
     */
    public function store(Proposal $proposal)
    {
        $data = request()->validate([
            'address' => 'required|string',
            'setup_on' => 'required|date',
            'description' => 'nullable|string',
            'fee' => 'nullable|numeric',
        ]);

        $setup = new Setup();
        $setup->address = $data['address'];
        $setup->setup_on = Carbon::parse($data['setup_on']);
        $setup->description = $data['description'];
        $setup->fee = $data['fee'];
        $setup->account()->associate(Auth::user()->account);
        $setup->proposal()->associate($proposal);
        $setup->save();

        return response()->json($setup);
    }
}
