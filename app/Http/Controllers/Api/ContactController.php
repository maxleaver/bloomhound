<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Contact;
use App\Customer;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('in_account:contact')
        ->except(['index', 'store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Auth::user()->account->contacts;
        return response()->json($contacts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->validate([
            'customer_id' => 'required|integer',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'relationship' => 'nullable|string|max:255',
        ]);

        $customer = Customer::findOrFail($data['customer_id']);
        if ($customer->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $contact = new Contact($data);
        $contact->account()->associate(Auth::user()->account);
        $contact->customer()->associate($customer);
        $contact->save();

        return response()->json($contact);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return response()->json($contact);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Contact              $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Contact $contact)
    {
        $data = request()->validate([
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'relationship' => 'nullable|string|max:255',
        ]);

        $contact->update($data);
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
