<?php

namespace App\Http\Controllers\Api;

use Auth;
use DB;
use App\ArrangeableTypeSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArrangeableTypeSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account = Auth::user()->account;
        return response()->json($account->arrangeable_type_settings->load('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = [
            '*.arrangeable_type_id' => 'required|integer|exists:arrangeable_types,id',
            '*.markup_id' => 'required|integer|exists:markups,id',
            '*.markup_value' => 'nullable|numeric',
        ];

        foreach ($request->all() as $index => $entry) {
            if (isset($exists['markup_value']) || array_key_exists('markup_value', $entry)) {
                $rules[$index . '.markup_value'] = [
                    new \App\Rules\RequiresMarkupValue($entry['markup_id']),
                ];
            }
        }

        $request->validate($rules);

        $settings = array();

        DB::transaction(function () use ($request, &$settings) {
            $accountId = Auth::user()->account->id;

            foreach ($request->all() as $record) {
                // Get the settings record
                $setting = ArrangeableTypeSetting::whereAccountId($accountId)
                    ->where('arrangeable_type_id', $record['arrangeable_type_id'])
                    ->first();

                if (!$setting) {
                    // Setting doesn't exist for the account
                    // Realistically this shouldn't happen,
                    // so if it happens, we definitely want to know.
                    Log::error('Arrangeable setting ' . $record['arrangable_type_id'] .
                        ' doesn\'t exist for account ' . $accountId);

                    abort(403);
                }

                if (isset($record['markup_value'])) {
                    $setting->markup_value = $record['markup_value'];
                }

                $setting->markup()->associate($record['markup_id']);
                $setting->save();
            }
        });
    }
}
