<?php

namespace App\Providers;

use App\Time;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class TimeServiceProvider extends ServiceProvider
{
    protected $timeModel;

    public function __construct(Time $time)
    {
        $this->timeModel = $time;
    }

    public function index($project_id, $member_id){

        $times = $this->timeModel->where('project_id',$project_id)->where('member_id',$member_id)->with('task')->get();

        return [
            'status' => 'success',
            'data' => $times
        ];

    }

    public function create(array $data){
       $check = $this->timeModel->newQuery();
       $check->where('member_id', $data['member_id'])->where('project_id',$data['project_id']);
        if ( $data['date']){
            $check->where("date",$data['date']);
        }else{
            $today = Carbon::today()->toDateString();
            $check->where("date",$today);
        }
        if (array_key_exists('task',$data) && $data['task']){
            $check->where("task_id",$data['task']);
        }else{
            $project = 0;
            $check->where("task_id",$project);
        }
        if ($check->exists()){
            $check->update([
                'time' =>  $data['time']
            ]);
        }else{
          $record = new Time();
        $record->member_id = $data['member_id'];
        $record->project_id = $data['project_id'];
        $record->time = $data['time'];
        if ( $data['date']){
            $record->date = $data['date'];
        }else{
            $today = Carbon::today()->toDateString();
            $record->date = $today;
        }
        if (array_key_exists('task',$data) && $data['task']){
            $record->task_id = $data['task'];
        }
        if($record->save()){
            return response()->json('success', 200);
        }
        return response()->json('error', 500);
        }

    }
}
