<?php

namespace App\Http\Controllers;

use App\Providers\NoteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    protected $noteService;

    public function __construct(NoteServiceProvider $noteService)
    {
        $this->noteService = $noteService;
    }


    public function index(Request $request){
        if ($request->route('project')){
            $response = $this->noteService->index($request->route('project'));
            return response()->json($response);
        }
    }

    public function create(Request $request){

        $v = Validator::make($request->all(), [
            'project_id' => 'required',
            'author' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $response = $this->noteService->create($request->all());
        return response()->json($response);
    }

    public function update(Request $request){

        $v = Validator::make($request->all(), [
            'note_id' => 'required',
            'title' => 'nullable',
            'content' => 'nullable',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $response = $this->noteService->update($request->all());
        return response()->json($response);
    }
}
