<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return response()->json(['name' => $user->name, 'email' => $user->email]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $user = Auth::user();

        $data = request()->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($data);
    }
}
