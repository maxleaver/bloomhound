<?php

namespace App\Http\Controllers\Api;

use Auth;
use Exception;
use App\Note;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('in_account:note.user')->except(['index', 'store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $class = $this->makeClass($request->segment(2), $id);
        return response()->json($class->notes);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $data = request()->validate([
            'text' => 'required|string',
        ]);

        $class = $this->makeClass($request->segment(2), $id);

        $note = new Note($data);
        $note->notable_id = $id;
        $note->notable_type = get_class($class);
        $note->user()->associate(Auth::user());
        $note->save();

        return response()->json($note);
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
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Note $note)
    {
        $data = request()->validate([
            'text' => 'required|string',
        ]);

        $note->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Note $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        $note->delete();

        return response()->json();
    }

    protected function getClassType($string)
    {
        $type = substr($string, 0, -1);
        $type = 'App\\' . ucfirst($type);

        // Verify class exists
        if (!class_exists('\\' . $type)) {
            throw new Exception('Class ' . $type . ' does not exist');
        }

        return $type;
    }

    protected function makeClass($string, $id)
    {
        try {
            $classType = $this->getClassType($string);

            // Find the record in the DB
            $class = $classType::with('account')->where('id', $id)->first();

            // Verify the record exists
            if (!$class) {
                throw new Exception('Could not find model ' . $classType . ' matching id ' . $id);
            }

            if (!method_exists($class, 'account')) {
                throw new Exception('Model ' . $classType . ' with ID ' . $id . ' does not have an account');
            }

            // Verify account matches our authenticated user
            if ($class->account->id !== Auth::user()->account->id) {
                throw new Exception('Model ' . $classType . ' with ID ' . $id . ' does not belong to the account of user ' . Auth::user()->id);
            }
        } catch (Exception $e) {
            abort(404);
        }

        return $class;
    }
}
