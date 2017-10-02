<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Event;
use App\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventVendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function index(Event $event)
    {
        if ($event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        return response()->json($event->vendors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Event                $event
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Event $event, Request $request)
    {
        $data = $this->validate(request(), [
            'vendor_id' => 'nullable|integer|required_without:vendor_name',
            'vendor_name' => 'nullable|string|required_without:vendor_id|max:255',
        ]);

        if ($event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        if (isset($data['vendor_id'])) {
            $vendor = Vendor::find($data['vendor_id']);

            if ($vendor->account->id !== Auth::user()->account->id) {
                abort(403);
            }

            $event->vendors()->attach($vendor);
            return response()->json($vendor);
        }

        // Make a new vendor
        $vendor = new Vendor($data);
        $vendor->name = $data['vendor_name'];
        $vendor->account()->associate(Auth::user()->account);
        $vendor->save();

        $event->vendors()->attach($vendor);
        return response()->json($vendor);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
