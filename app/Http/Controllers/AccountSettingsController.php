<?php

namespace App\Http\Controllers;

use Auth;
use App\Markup;
use Illuminate\Http\Request;

class AccountSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account = Auth::user()->account;
        $markups = Markup::all();
        return view('account.settings.index', compact('account', 'markups'));
    }
}
