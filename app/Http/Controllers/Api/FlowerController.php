<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Flower;
use App\FlowerLibrary;
use App\Http\Controllers\Controller;

class FlowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Auth::user()->account->flowers->load('varieties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create a new flower
        $flower = new Flower;
        $flower->name = $data['name'];
        $flower->account()->associate(Auth::user()->account);
        $flower->save();

        return response()->json($flower->fresh());
    }
}
