<?php

namespace App\Providers;

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

        if (!empty($filters['member']))
        {
            $member_id = $filters['member'];
            $projects = $projects->whereHas('members',function (Builder $query) use ($member_id){
                $query->where('member_id', '=', $member_id);
            })->with('members')->get();
        }else{
            $projects = $projects->with('members')->get();
        }

        return [
            'status' => 'success',
            'data' => $projects
        ];
    }

    public function project($project_id){
        $project = $this->projectModel->where('id',$project_id)->first();
        return [
            'status' => 'success',
            'data' => $project
        ];

    }

    public function create(array $data){

        $project = new Project();
        $project->name = $data['name'];
        $project->owner = $data['owner'];
        $project->support_email = $data['support_email'];
        if ($data['tasks']){
            $project->tasks_list = json_encode($data['tasks']);
        }
//        $project->members = json_encode($data['members']);
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

    public function members($project_id){
        $members = MemberToProject::where('project_id',$project_id)->with('member')->get();
        return [
            'status' => 'success',
            'data' => $members
        ];
    }
}
