<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Contact;
use App\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Auth::user()->account->contacts;
        return response()->jsend_success($contacts);
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'relationship' => 'nullable|string|max:255',
            'customer_id' => 'required|integer'
        ]);

        $customer = Customer::findOrFail($data['customer_id']);
        if ($customer->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $contact = new Contact($data);
        $contact->account()->associate(Auth::user()->account);
        $contact->customer()->associate($customer);
        $contact->save();

        return response()->jsend_success($contact);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        if ($contact->account->id !== Auth::user()->account->id) {
            abort(404);
        }

        return response()->jsend_success($contact);
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
