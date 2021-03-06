<?php

namespace App\Http\Controllers\Api;

use Auth;
use DB;
use App\FlowerVariety;
use App\FlowerVarietySource;
use App\Vendor;
use App\Http\Requests\StoreFlowerVarietySource;
use App\Http\Controllers\Controller;

class FlowerVarietySourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('in_account:flower_variety');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\FlowerVariety  $flower_variety
     * @return \Illuminate\Http\Response
     */
    public function index(FlowerVariety $flower_variety)
    {
        $sources = FlowerVarietySource::where([
            'account_id' => Auth::user()->account->id,
            'flower_variety_id' => $flower_variety->id,
        ])->get();

        return response()->json($sources);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\FlowerVariety  $flower_variety
     * @param  \App\Http\Requests\StoreFlowerVarietySource  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FlowerVariety $flower_variety, StoreFlowerVarietySource $request)
    {
        $sources = [];

        DB::transaction(function () use ($flower_variety, $request, &$sources) {
            foreach ($request->all() as $record) {
                if (array_key_exists('vendor_id', $record) && !is_null($record['vendor_id'])) {
                    $vendor = $this->getVendor($record['vendor_id']);
                } else {
                    $vendor = $this->createVendor($record['vendor_name']);
                }

                // Create a new flower variety source
                $source = new FlowerVarietySource;
                $source->cost = $record['cost'];
                $source->stems_per_bunch = $record['stems_per_bunch'];
                $source->account()->associate(Auth::user()->account);
                $source->variety()->associate($flower_variety);
                $source->vendor()->associate($vendor);
                $source->save();

                array_push($sources, $source);
            }
        });

        return response()->json($sources);
    }

    protected function getVendor($id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor || $vendor->account->id !== Auth::user()->account->id) {
            // Vendor is not in users account
            abort(403);
        }

        return $vendor;
    }

    protected function createVendor($name)
    {
        $vendor = new Vendor;
        $vendor->name = $name;
        $vendor->account()->associate(Auth::user()->account);
        $vendor->save();
        return $vendor;
    }
}
