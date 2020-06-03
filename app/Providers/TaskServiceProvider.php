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

    public function index($project_id){

        $tasks = $this->taskModel->all();
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
        $task->assignee_id = $data['assignee'];
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
        return response()->json('success', 200);
    }
}
