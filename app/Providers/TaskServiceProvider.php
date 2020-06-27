<?php

namespace App\Providers;

use App\Comment;
use App\File;
use App\MemberToProject;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use App\Task;

class TaskServiceProvider
{
    protected $taskModel;

    public function __construct(Task $task)
    {
        $this->taskModel = $task;
    }

    public function index($data){
        $tasks = $this->taskModel->with('assignee')->newQuery();
        $tasks = $tasks->where('project_id',$data['project_id']);
        if (array_key_exists('assignees', $data) && $data['assignees'] != 'null' ){
            $assignees = json_decode($data['assignees']);
            if (count($assignees)){
                $tasks = $tasks->whereIn('assignee_id',$assignees);
            }
        }
        if (array_key_exists('due_on', $data) && $data['due_on'] != 'null'){
            $tasks = $tasks->where('due_on',$data['due_on']);
        }
        $tasks = $tasks->get();
        $collection = collect($tasks);
        $grouped = $collection->groupBy('task_list');
        $grouped->toArray();
        return[
            'status' => 'success',
            'data' => $grouped
        ];
    }

    public function create(array $data){
        $task = new Task();
        $task->name = $data['name'];
        $task->project_id = $data['project_id'];
        if (array_key_exists('assignee',$data) && $data['assignee']){
            $task->assignee_id = $data['assignee'];
        }
        if ($task->save()){

            activity()
                ->causedBy(Auth::user()->id)
                ->performedOn(new Task())
                ->withProperties(['project' => $data['project_id']])
                ->createdAt(now())
                ->log(Auth::user()->first_name .' '. Auth::user()->last_name . ' added new task.');

            $members = MemberToProject::where('project_id', $data['project_id'])->where('member_id', '!=', Auth::user()->id)->with('member')->with('project')->get();
            $client = new Client();
            foreach ($members as $member){
                $json = [
                    'user' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                    'name' => $data['name'],
                    'task' => $task->id,
                    'project' => $member->project->name,
                    'project_id' => $data['project_id']
                ];
                $email = $member->member->email;
                $response = $client->post('https://api.ravenhub.io/company/XiNNmnN7cU/subscribers/'.$email.'/events/1PY685slKM', [
                    'headers' => [],
                    'json' => $json,
                ]);
            }

            return response()->json('success', 200);
        }
        return response()->json('error', 500);
    }

    public function update(array $data){
        foreach($data as $list => $tasks){
            foreach ($tasks as $task){
                $task = $this->taskModel->updateOrCreate([
                    'id' => $task['id']
                ],[
                    'name' => $task['name'],
                    'project_id' => $task['project_id'],
                    'assignee_id' => $task['assignee_id'],
                    'label' => $task['label'],
                    'task_list' => $list,
                    'due_on' =>  $task['due_on']
                ]);
            }
        }
    }

    public function updateTask(array $data){
        $update = $this->taskModel->find($data['id'])->update([
           'details' => $data['details'],
           'due_on' => $data['due_on'],
           'assignee_id' => $data['assignee_id']
       ]);
       if ($update){
           return response()->json('success', 200);
       }
        return response()->json('error', 500);
    }

    public function delete($id){

        $comments = Comment::where('task_id',$id)->get();

        foreach ($comments as $comment){
            $files = File::where('comment_id',$comment->id)->get();
            foreach ($files as $file){
                $explode = explode('/',$file->file_path);
                Storage::disk('public')->delete('files/' . end($explode) );
                $file->delete();
            }
            $comment->delete();
        }

        $task = $this->taskModel->find($id)->delete();
        if ($task){
            return response()->json('success', 200);
        }
        return response()->json('error', 500);
    }
}
