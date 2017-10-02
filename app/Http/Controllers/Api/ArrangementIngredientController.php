<?php

namespace App\Http\Controllers\Api;

use App\Arrangement;
use App\ArrangementIngredient;
use App\Rules\IsArrangeable;
use Auth;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArrangementIngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Arrangement  $arrangement
     * @return \Illuminate\Http\Response
     */
    public function index(Arrangement $arrangement)
    {
        if ($arrangement->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        return response()->json($arrangement->ingredients);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Arrangement  $arrangement
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Arrangement $arrangement, Request $request)
    {
        $data = $this->validate(request(), [
            '*.id' => 'required|integer',
            '*.type' => ['required', 'string', 'max:255', new IsArrangeable],
            '*.quantity' => 'required|integer',
        ]);

        $accountId = Auth::user()->account->id;

        if ($arrangement->account->id !== $accountId) {
            abort(403);
        }

        $ingredients = [];
        $idsByModel = [];

        foreach($request->all() as $entry) {
            $type = $this->getArrangeableClass($entry['type']);
            if (!$type) {
                // Validation should have caught this,
                // but didn't for some reason.
                throw new Exception('Validation failed to catch an invalid arrangeable type');
            }

            // Group the IDs of each arrangeable by model
            // to check that they're in our users account later
            if (!array_key_exists($type, $idsByModel)) {
                $idsByModel[$type] = [];
            }
            array_push($idsByModel[$type], $entry['id']);

            $ingredients[] = [
                'arrangeable_id' => $entry['id'],
                'arrangeable_type' => $type,
                'quantity' => $entry['quantity'],
            ];
        }

        // Loop through each model type and find any IDs that aren't
        // in our users account
        foreach ($idsByModel as $model => $idArray) {
            $arrangeables = $model::whereIn('id', $idArray)->get();

            // Compare the IDs we got back against our ID array
            $invalidIds = array_values(
                array_diff($idArray, $arrangeables->pluck('id')->toArray())
            );

            // If any IDs didn't match existing entries, cancel the request
            if ($invalidIds) {
                abort(403);
            }

            // Filter out any models that aren't in our users account
            $areInAccount = $this->inUsersAccount($arrangeables, $accountId);
            if (!$areInAccount) {
                abort(403);
            }
        }

        // TODO: This is really ugly. There's got to be a better way to get the ingredients
        // after they've been added. The create method returns the raw arrangeables.
        $result = $arrangement->ingredients()->createMany($ingredients);
        $added = ArrangementIngredient::whereIn('id', $result->pluck('id')->toArray())->get();
        return response()->json($added);
    }

    protected function getArrangeableClass($type)
    {
        switch ($type) {
            case 'flowervariety':
                return 'App\FlowerVariety';
            case 'item':
                return 'App\Item';
            default:
                return false;
        }
    }

    /**
     * Verifies an array of models are in the authenticated users account
     * @param  array $records
     * @param  int   $accountId
     * @return bool
     */
    protected function inUsersAccount($records, $accountId)
    {
        $inAnotherAccount = $records->filter(function ($value, $key) use ($accountId) {
            return $value->account->id !== $accountId;
        });

        return count($inAnotherAccount) === 0;
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
     * @param  App\Arrangement              $arrangement
     * @param  App\ArrangementIngredient    $ingredient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Arrangement $arrangement, ArrangementIngredient $ingredient)
    {
        if ($arrangement->account->id !== Auth::user()->account->id) {
            abort(403);
        }

        $ingredient->delete();
    }
}
