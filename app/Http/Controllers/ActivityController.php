<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
       public function index(Request $request){
           $today = Carbon::today()->toDateString();
           if ($request->route('project_id')){
                $activity = Activity::where('properties->project',$request->route('project_id'))->get();
                if ($activity){

                    $collection = collect($activity)->filter(function ($item) use ($today) {
                        $createdAt = Carbon::parse(  $item->created_at);
                        $item->date = $createdAt->format('Y-m-d H:i');
                        return false !== stristr($item->created_at, $today);
                    })->toArray();

                    return [
                        'status' => 'success',
                        'data' => $collection
                    ];
                }
            }
            return response()->json('error',500);
    }
}
