<?php

namespace App\Http\Controllers;

use App\Providers\TimeServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TimeController extends Controller
{
    protected $timeService;

    public function __construct(TimeServiceProvider $timeService)
    {
        $this->timeService = $timeService;
    }

    public function index(Request $request){

        $response = $this->timeService->index($request->route('project'),$request->route('member'));
        return response()->json($response);
    }

    public function delete(Request $request){
        $v = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $response = $this->timeService->delete($request->get('id'));
        return response()->json($response);
    }

    public function create(Request $request){
        $v = Validator::make($request->all(), [
            'project_id' => 'required',
            'member_id' => 'required',
            'time' => ['required',
                'regex:/(\d+\:|.\d+)/'
            ]
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $response = $this->timeService->create($request->all());
        return response()->json($response);
    }

    public function monthlyTime(){
        $response = $this->timeService->monthlyTime();
        return response()->json($response);
    }
}
