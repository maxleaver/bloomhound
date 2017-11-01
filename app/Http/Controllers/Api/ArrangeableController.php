<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Controllers\Controller;

class ArrangeableController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = [
        	'App\Item',
        	'App\FlowerVariety',
        ];

        $arrangeables = [];
        foreach($types as $type) {
            $results = $type::where('account_id', Auth::user()->account->id)->get();
            $arrangeables = array_merge($arrangeables, $results->toArray());
        }

        return response()->json($arrangeables);
    }
}
