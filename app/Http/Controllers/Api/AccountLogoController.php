<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccountLogoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->validate([
            'logo' => 'required|image|mimes:jpeg,jpg,bmp,png,gif|dimensions:min_width=100,min_height=100',
        ]);

        $account = Auth::user()->account;

        // Save the uploaded file
        $path_to_file = $request->file('logo')->store('public/logos');

        if ($account->logo) {
            // Delete the old file
            Storage::delete($account->logo);
        }

        // Add the path to the account record
        $account->update(['logo' => $path_to_file]);

        return response()->json($account->logo_path);
    }
}
