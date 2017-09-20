<?php

namespace App\Http\Controllers\Api;

use App\Flower;
use App\FlowerLibrary;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FlowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->input('lib')) {
            $library = FlowerLibrary::whereType($request->input('lib'))->first();

            if (!$library) {
                abort(404);
            }

            if ($library->type === 'custom') {
                // Get flowers that match the account ID
                return response()->json(Auth::user()->account->flowers);
            }

            return response()->json($library->flowers);
        }

        return response()->json(Flower::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate(request(), [
            'name' => 'required|string|max:255',
        ]);

        // Create a new flower
        $flower = new Flower;
        $flower->name = $data['name'];
        $flower->account()->associate(Auth::user()->account);
        $flower->library()->associate(FlowerLibrary::whereType('custom')->first());
        $flower->save();

        return response()->json($flower->fresh());
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
