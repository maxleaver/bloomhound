<?php

namespace App\Http\Controllers\Api;

use App\Flower;
use App\FlowerVariety;
use Auth;
use Illuminate\Http\Request;
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

        return response()->json($flower->varieties->load('sources', 'sources.vendor'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  \App\Flower  $flower
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Flower $flower, Request $request)
    {
        if ($flower->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $data = $this->validate(request(), [
            'name' => 'required|string|max:255',
        ]);

        // Create a new flower variety
        $variety = new FlowerVariety;
        $variety->name = $request->name;
        $variety->flower()->associate($flower);
        $variety->save();

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
