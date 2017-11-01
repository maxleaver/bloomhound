<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Event;
use App\Vendor;
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
     * @return \Illuminate\Http\Response
     */
    public function store(Event $event)
    {
        $data = request()->validate([
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
        $vendor = new Vendor();
        $vendor->name = $data['vendor_name'];
        $vendor->account()->associate(Auth::user()->account);
        $vendor->save();

        $event->vendors()->attach($vendor);
        return response()->json($vendor);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @param  \App\Vendor $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event, Vendor $vendor)
    {
        if ($event->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        if (!$vendor->events->contains($event)) {
            abort(404);
        }

        $event->vendors()->detach($vendor);
    }
}
