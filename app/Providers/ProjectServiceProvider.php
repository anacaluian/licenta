<?php

namespace App\Providers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;


class ProjectServiceProvider
{
    protected $projectModel;

    public function __construct(Project $project)
    {
        $this->projectModel = $project;
    }

    public function index(array $filters){
        $projects =  $this->projectModel->newQuery();

        if (!empty($filters['member']))
        {
            $projects->where('members', 'LIKE', "%{$filters['member']}%");
        }

        $projects = $projects->get();

        return [
            'status' => 'success',
            'data' => $projects
        ];
    }

    public function create(array $data){

        $project = new Project();
        $project->name = $data['name'];
        $project->owner = $data['owner'];
        $project->support_email = $data['support_email'];
        $project->members = json_encode($data['members']);
        if($project->save()){
            return response()->json('success', 200);
        }

        return response()->json('error', 500);
    }

    public function edit(array $data){

        $project = $this->projectModel::find($data['id']);
        $project->name = $data['name'];
        $project->owner = $data['owner'];
        $project->support_email = $data['support_email'];
        if ($data['members']){
            $project->members = json_encode($data['members']);
        }else{
            $project->members = null;
        }
        if ($data['clients']){
            $project->clients = json_encode($data['clients']);
        }else{
            $project->clients = null;
        }
        if($project->save()){
            return response()->json('success', 200);
        }

        return response()->json('error', 500);
    }

    public function state(array $data){

        $project = $this->projectModel::find($data['id']);
        if ($data['state'] == 'in_progress'){
            $project->state = 'completed';
        }else{
            $project->state = 'in_progress';
        }
        if($project->save()){
            return response()->json('success', 200);
        }

        return response()->json('error', 500);
    }

    public function delete(array $data){

        $project = $this->projectModel::find($data['id']);
        if($project->delete()){
            return response()->json('success', 200);
        }

        return response()->json('error', 500);

    }
}
