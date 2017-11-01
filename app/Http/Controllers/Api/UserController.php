<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('account_id', Auth::user()->account_id)->get();
        return response()->json($users);
    }
}
