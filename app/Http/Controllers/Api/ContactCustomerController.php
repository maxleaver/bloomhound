<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Customer;
use App\Http\Controllers\Controller;

class ContactCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Customer $customer)
    {
        if ($customer->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        return response()->json($customer->contacts);
    }
}
