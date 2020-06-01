<?php

namespace App\Http\Controllers;

use App\Project;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index(){
       $projects =  Project::all();
        return [
            'status' => 'success',
            'data' => $projects
        ];
    }

    public function create(Request $request){

        $v = Validator::make($request->all(), [
            'name' => 'required',
            'owner' => 'required',
            'members' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $project = new Project();
        $project->name = $request->get('name');
        $project->owner = $request->get('owner');
        $project->members = json_encode($request->get('members'));
        if($project->save()){
            return response()->json('success', 200);
        }

        return response()->json('error', 500);

    }
}
