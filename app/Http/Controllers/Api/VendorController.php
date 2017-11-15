<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Vendor;
use App\Http\Controllers\Controller;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('in_account:vendor')->only('update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Auth::user()->account->vendors;
        return response()->json($vendors);
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
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255'
        ]);

        $vendor = new Vendor($data);
        $vendor->account()->associate(Auth::user()->account);
        $vendor->save();

        return response()->json($vendor);
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
     * @param  \App\Vendor               $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Vendor $vendor)
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
        ]);

        $vendor->update($data);
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
