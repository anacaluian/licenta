<?php

namespace App\Http\Controllers;

use App\Providers\GmailServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GmailController extends Controller
{
    protected $gmailService;

    public function __construct(GmailServiceProvider $gmailService)
    {
        $this->gmailService = $gmailService;
    }

    public function index(){
       $response =  $this->gmailService->index();
        return response()->json($response);
    }

    public function task(Request $request){
        $v = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $response =  $this->gmailService->task($request->get('id'));
        return response()->json($response);
    }

    public function remove(Request $request){
        $v = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $response =  $this->gmailService->remove($request->get('id'));
        return response()->json($response);
    }

    public function sync(){
        $this->gmailService->getUnreadMails();
    }

    public function delete(Request $request){
        $v = Validator::make($request->all(), [
            'email_id' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $response =  $this->gmailService->deleteEmail($request->get('email_id'));
        return response()->json($response);
    }

    public function requestAuthLink(){
        $response =  $this->gmailService->requestAuthLink();
        return response()->json($response);
    }

    public function generateToken(Request $request){
        $v = Validator::make($request->all(), [
            'code' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $response =  $this->gmailService->generateToken($request->get('code'));
        return response()->json($response);
    }
}
