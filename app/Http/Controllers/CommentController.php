<?php

namespace App\Http\Controllers;

use App\Providers\CommentServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentServiceProvider $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request){
        if ( $request->route('project') && $request->route('task')){
            $response = $this->commentService->index($request->route('project'), $request->route('task'));
            return response()->json($response);
        }

    }

    public function create(Request $request){
        $v = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
            'task_id' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $response = $this->commentService->create($request->all());
        return response()->json($response);

    }

    public function upload(Request $request){
        $v = Validator::make($request->all(), [
            'files' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $response = $this->commentService->upload($request->route('task'),$request->file('files'));
        return response()->json($response);
    }
}
