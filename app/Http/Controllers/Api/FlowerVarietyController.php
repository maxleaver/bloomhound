<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Flower;
use App\FlowerVariety;
use App\Services\CreateFlowerVarietyService;
use App\Http\Controllers\Controller;

class FlowerVarietyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Flower $flower)
    {
        if ($flower->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $varieties = $flower->varieties->load('sources', 'sources.vendor', 'best_source');
        return response()->json($varieties);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \App\Flower  $flower
     * @return \Illuminate\Http\Response
     */
    public function store(Flower $flower)
    {
        if ($flower->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $data = request()->validate([
            'name' => 'required|string|max:255',
        ]);

        $variety = (new CreateFlowerVarietyService($data['name'], $flower))->make();
        return response()->json($variety);
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
     * @param  \App\FlowerVariety  $flower_variety
     * @return \Illuminate\Http\Response
     */
    public function update(FlowerVariety $flower_variety)
    {
        if ($flower_variety->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $data = request()->validate([
            'name' => 'required|string|max:255',
            'markup_id' => 'nullable|integer|exists:markups,id',
            'markup_value' => 'nullable|numeric',
            'use_default_markup' => 'nullable|boolean',
        ]);

        $flower_variety->update($data);

        return response()->json($flower_variety->fresh());
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
