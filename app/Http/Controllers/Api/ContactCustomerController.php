<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use App\Http\Controllers\Controller;

class ContactCustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('in_account:customer');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Customer $customer)
    {
        return response()->json($customer->contacts);
    }
}
