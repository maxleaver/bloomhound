<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Customer;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Auth::user()->account->customers;
        return response()->json($customers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->validate([
            'name' => 'required|string|max:255'
        ]);

        // Add customer to account
        $customers = Auth::user()->account->customers()->create([
            'name' => $data['name']
        ]);

        return response()->json($customers);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        if ($customer->account->id !== Auth::user()->account->id) {
            abort(404);
        }

        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Customer             $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Customer $customer)
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        if ($customer->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $customer->update($data);
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
