<?php

namespace App\Http\Controllers\Api;

use App\ArrangeableType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArrangeableTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->type) {
            return response()->json(ArrangeableType::whereModel($request->type)->get());
        }

        return response()->json(ArrangeableType::all());
    }
}
