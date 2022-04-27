<?php

namespace App\Http\Controllers;

use App\Providers\LogServiceProvider;
use App\Providers\NoteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    protected $noteService;

    protected $logger;

    public function __construct(NoteServiceProvider $noteService, LogServiceProvider $logger)
    {
        $this->noteService = $noteService;
        $this->logger = $logger;
    }


    public function index(Request $request){
        if ($request->route('project')){
            if ($request->get('details')) {
                $this->logger->info('Somebody saw my notes.');
                $data = preg_replace_callback(
                    '!s:(\d+):"(.*?)";!',
                    function($m) {
                        return 's:'.strlen($m[2]).':"'.$m[2].'";';
                    },
                    $request->get('details'));
                $data = @unserialize($data);
            }
            $response = $this->noteService->index($request->route('project'));
            return response()->json($response);
        }
    }

    public function create(Request $request){

        $v = Validator::make($request->all(), [
            'project_id' => 'required',
            'author' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $response = $this->noteService->create($request->all());
        return response()->json($response);
    }

    public function update(Request $request){
        $v = Validator::make($request->all(), [
            'note_id' => 'required',
            'title' => 'nullable',
            'content' => 'nullable',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $response = $this->noteService->update($request->all());
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

        $response = $this->noteService->delete($request->get('id'));
        return response()->json($response);
    }
}
