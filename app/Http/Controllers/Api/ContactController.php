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
        return response()->json($contacts);
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
        if ($contact->account->id !== Auth::user()->account->id) {
            abort(404);
        }

        return response()->json($contact);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact              $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $data = $this->validate(request(), [
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'relationship' => 'nullable|string|max:255',
        ]);

        if ($contact->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $contact->update([
            'address' => $data['address'],
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'relationship' => $data['relationship'],
        ]);
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
