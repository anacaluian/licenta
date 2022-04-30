<?php

namespace App\Http\Controllers;

use App\Providers\FileServiceProvider;
use App\Providers\LogServiceProvider;
use function Couchbase\defaultDecoder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    protected $logger;

    protected $fileService;

    public function __construct(FileServiceProvider $fileService, LogServiceProvider $logger)
    {
        $this->fileService = $fileService;
        $this->logger = $logger;
    }

    public function index(Request $request){
        if ($request->route('project')){
            $response = $this->fileService->index($request->route('project'));
            return response()->json($response);
        }
    }

    public function create(Request $request){
        $v = Validator::make($request->all(), [
            'project' => 'required',
            'owner' => 'required',
            'files' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $response = $this->fileService->create($request->all());
        return response()->json($response);
    }


    public function download(Request $request){
        if ($request->route('file')){
            $response = $this->fileService->download($request->route('file'));
            return $response;
        }
    }
}
