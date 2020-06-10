<?php

namespace App\Providers;

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
        $tasks = $this->taskModel->newQuery();
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
        $tasks = $tasks->with('assignee')->get();
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
           'due_on' => $data['due_on']
       ]);
       if ($update){
           return response()->json('success', 200);
       }
        return response()->json('error', 500);
    }
}
