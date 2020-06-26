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
        $carbon = Carbon::now();
        $times =  $this->timeModel->newQuery();
        if ($project_id){
            $times = $times->where('project_id',$project_id);
        }

        if ($member_id){
            $times = $times->where('member_id',$member_id);
        }

        if (!$project_id && !$member_id){
            $times = $this->timeModel->with('task')->with('project')->get();
            $collection = collect($times);
            $grouped = $collection->groupBy('project.name');
            $grouped->toArray();

            return[
                'first_day' => $carbon->startOfMonth()->toDateString(),
                'last_day' => $carbon->endOfMonth()->toDateString(),
                'status' => 'success',
                'data' => $grouped
            ];
        }

        $times = $times->with('task')->with('project')->get();

        return [
            'first_day' => $carbon->startOfMonth()->toDateString(),
            'last_day' => $carbon->endOfMonth()->toDateString(),
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
            $record->description = $data['description'];
        if($record->save()){
            return response()->json('success', 200);
        }
        return response()->json('error', 500);
        }

    }

    public function monthlyTime(){
        $data = [];
        $carbon = Carbon::now();
        $times  = $this->timeModel->whereBetween('date', [$carbon->startOfMonth()->toDateString(), $carbon->endOfMonth()->toDateString()])->get();
        $collection = collect($times);
        $grouped = $collection->groupBy('project.name');
        $grouped->toArray();
        foreach ($grouped as $key => $project){
            $hours = 0;
            $minutes = 0;
            $seconds = 0;
            $rate = 0;
            $obj = new \stdClass;
            $obj->name = $key;
            $obj->from = $project->min('date');
            $obj->to = $project->max('date');
            foreach ($project as $item) {
                $rate = $item->project->rate;
                $parse = date_parse($item->time);
                $seconds += 3600 *  $parse['hour'] + 60 * $parse['minute'];
                $hours += $parse['hour'];
                $minutes += $parse['minute'];
                if ($minutes > 60){
                    $floor = floor($minutes / 60);
                    $hours += $floor;
                    $minutes -= $floor * 60;
                }
            }
            $obj->human = \Carbon\CarbonInterval::seconds($seconds)->cascade()->forHumans();
            $obj->hours = intval($hours);
            $obj->minutes = intval($minutes);
            $obj->total = $seconds / 60 / 60 * $rate;
            array_push($data,$obj);
        }

        return[
            'status' => 'success',
            'data' => $data,
            'first_day' => Carbon::now()->startOfMonth()->toDateString(),
            'last_day' => Carbon::now()->endOfMonth()->toDateString(),
        ];
    }
    public function delete($id){
       $delete =  $this->timeModel->where('id',$id)->delete();
       if ($delete){
           return response()->json('success', 200);
       }
        return response()->json('error', 500);
    }
}
