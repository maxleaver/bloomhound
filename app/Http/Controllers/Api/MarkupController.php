<?php

namespace App\Http\Controllers\Api;

use App\Markup;
use App\Http\Controllers\Controller;

class MarkupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Markup::all());
    }
}
