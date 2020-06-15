<?php

namespace App\Providers;

use App\ClientToProject;
use App\MemberToProject;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class ProjectServiceProvider
{
    protected $projectModel;

    public function __construct(Project $project)
    {
        $this->projectModel = $project;
    }

    public function index(array $filters){
        $projects =  $this->projectModel->newQuery();
        if (!empty($filters['member'] ))
        {
            $projects->where('state' ,'!=' ,'completed');
            $member_id = $filters['member'];
            if ($filters['role'] == 2){
                $projects = $projects->whereHas('members',function (Builder $query) use ($member_id){
                    $query->where('member_id', '=', $member_id);
                })->with('members_project')
                    ->with('clients_project')
                    ->get();
            }
            if ($filters['role'] == 3){
                $projects = $projects->whereHas('clients',function (Builder $query) use ($member_id){
                    $query->where('client_id', '=', $member_id);
                })->with('members_project')
                    ->with('clients_project')
                    ->get();
            }
            if ($filters['role'] == 1){
                $projects = $projects->with('members_project')
                    ->with('clients_project')
                    ->get();
            }

        }else{
            $projects = $projects->with('members_project')->with('clients_project')->get();
        }

        return [
            'status' => 'success',
            'data' => $projects
        ];
    }

    public function project(array $data){

        $project = $this->projectModel->where('id',$data['id'])->newQuery();

        if (!array_key_exists('assignee',$data) && !array_key_exists('due',$data)){
            $project = $project->with('tasks')->first();
        }
        if (array_key_exists('assignee',$data)){
            $project = $project->with(['tasks' => function($query) use ($data){
                $query->where('assignee_id', $data['assignee']);
            }])->first();
        }
        if (array_key_exists('due',$data)){
            $project = $project->with(['tasks' => function($query) use ($data){
                $query->where('due_on', $data['due']);
            }])->first();
        }

        if (array_key_exists('assignee',$data) && array_key_exists('due',$data)){
            $project = $project->with(['tasks' => function($query) use ($data){
                $query->where('due_on', $data['due'])->where('assignee_id', $data['assignee']);
            }])->first();
        }
        return [
            'status' => 'success',
            'data' => $project
        ];

    }

    public function create(array $data){
        $project = new Project();
        $project->name = $data['name'];
        $project->owner = $data['owner'];
        if ( $data['rate']){
            $project->rate = $data['rate'];
        }
        $project->support_email = $data['support_email'];
        if ($data['tasks']){
            $project->tasks_list = json_encode($data['tasks']);
        }
          $project->save();
        $project_id = $project->id;
        if($project_id){
            foreach ($data['members'] as $key => $member_id){
                $item = new MemberToProject();
                $item->project_id = $project_id;
                $item->member_id = $member_id;
                $item->save();
            }
            return response()->json('success', 200);
        }

        return response()->json('error', 500);
    }

    public function edit(array $data){
        $project = $this->projectModel::find($data['id']);
        $project->name = $data['name'];
        $project->owner = $data['owner'];
        if ($data['rate']){
            $project->rate = $data['rate'];
        }
        $project->support_email = $data['support_email'];
        if ($data['tasks']){
            $project->tasks_list = json_encode($data['tasks']);
        }
        if ($data['members']){
            $clear = MemberToProject::where('project_id',$data['id'])->delete();
            foreach ($data['members'] as $key => $member_id){
                $item = new MemberToProject();
                $item->project_id = $data['id'];
                $item->member_id = $member_id;
                $item->save();
            }
        }else{
            $clear = MemberToProject::where('project_id',$data['id'])->delete();
        }
        if ($data['clients']){
            $clear = ClientToProject::where('project_id',$data['id'])->delete();
            foreach ($data['clients'] as $key => $client_id){
                $item = new ClientToProject();
                $item->project_id = $data['id'];
                $item->client_id = $client_id;
                $item->save();
            }
        }else{
            $clear = ClientToProject::where('project_id',$data['id'])->delete();
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

    public function members($project_id){
        $members = MemberToProject::where('project_id',$project_id)->with('member')->get();
        return [
            'status' => 'success',
            'data' => $members
        ];
    }
}
