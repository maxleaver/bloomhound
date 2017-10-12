<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\ArrangeableTypeSetting;
use App\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Auth::user()->account->items->load('type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate(request(), [
            'arrangeable_type_id' => 'required|integer|exists:arrangeable_types,id',
            'cost' => 'nullable|numeric',
            'description' => 'nullable|string|max:255',
            'inventory' => 'nullable|integer',
            'name' => 'required|string|max:255',
        ]);

        $account = Auth::user()->account;

        // Look up default markup for the item type
        $setting = ArrangeableTypeSetting::whereAccountId($account->id)
            ->whereArrangeableTypeId($request->arrangeable_type_id)
            ->first();

        $item = new Item($data);
        $item->markup()->associate($setting->markup);
        $item->markup_value = $setting->markup_value;
        $item->account()->associate($account);
        $item->save();

        return response()->json($item->load('type'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        if ($item->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        return response()->json($item);
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
