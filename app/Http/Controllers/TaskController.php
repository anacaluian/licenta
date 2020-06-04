<?php

namespace App\Http\Controllers;

use App\Providers\TaskServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskServiceProvider $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request){
        $v = Validator::make($request->all(), [
            'project_id' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $response = $this->taskService->index($request->all());
        return response()->json($response);
    }

    public function create(Request $request){

        $v = Validator::make($request->all(), [
            'project_id' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $response = $this->taskService->create($request->all());
        return response()->json($response);
    }

    public function update(Request $request){

        $response = $this->taskService->update($request->all());
        return response()->json($response);
    }

    public function updateTask(Request $request){

        $response = $this->taskService->updateTask($request->all());
        return response()->json($response);
    }
}
