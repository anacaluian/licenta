<?php

namespace App\Http\Controllers;

use App\Project;
use App\Providers\ProjectServiceProvider;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectServiceProvider $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(Request $request){

        $response = $this->projectService->index($request->all());
        return response()->json($response);

    }

    public function create(Request $request){

        $v = Validator::make($request->all(), [
            'name' => 'required',
            'owner' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $response = $this->projectService->create($request->all());
        return response()->json($response);
    }

    public function edit(Request $request){
        $response = $this->projectService->edit($request->all());
        return response()->json($response);
    }

    public function state(Request $request){
        $v = Validator::make($request->all(), [
            'id' => 'required',
            'state' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $response = $this->projectService->state($request->all());
        return response()->json($response);
    }

    public function delete(Request $request){
        $v = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $response = $this->projectService->delete($request->all());
        return response()->json($response);
    }
}
