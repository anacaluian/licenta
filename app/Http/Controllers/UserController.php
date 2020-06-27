<?php

namespace App\Http\Controllers;

use App\Providers\UserServiceProvider;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceProvider $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $response = $this->userService->index();
        return response()->json($response);
    }

    public function changePassword(Request $request)
    {
        $v = Validator::make($request->all(), [
            'id' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $response = $this->userService->changePassword($request->get('id'),$request->get('password'));
        return response()->json($response);
    }

    public function admin()
    {
        $response = $this->userService->admin();
        return response()->json($response);
    }

    public function changeRole(Request $request){
        $v = Validator::make($request->all(), [
            'id' => 'required',
            'current_role' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $response = $this->userService->changeRole($request->get('id'),$request->get('current_role'));
        return response()->json($response);
    }

    public function members()
    {
        $response = $this->userService->members();
        return response()->json($response);
    }

    public function membersEdit(Request $request){
        $v = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $response = $this->userService->membersEdit($request->all());
        return response()->json($response);
    }

    public function membersDelete(Request $request){
        $v = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $response = $this->userService->membersDelete($request->all());
        return response()->json($response);
    }

    public function clients(){
        $response = $this->userService->clients();
        return response()->json($response);
    }

    public function clientsEdit(Request $request){
        $v = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $response = $this->userService->clientsEdit($request->all());
        return response()->json($response);
    }

    public function clientsDelete(Request $request){
        $v = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $response = $this->userService->clientsDelete($request->all());
        return response()->json($response);
    }

    public function show($id)
    {
        $response = $this->userService->show($id);
        return response()->json($response);
    }

    public function profilePhoto(Request $request){
        $v = Validator::make($request->all(), [
            'user_id' => 'required',
            'file' => 'image',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $response = $this->userService->profilePhoto($request->all());
        return response()->json($response);
    }

    public function update(Request $request){
        $v = Validator::make($request->all(), [
            'user_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',

        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $response = $this->userService->update($request->all());
        return response()->json($response);
    }
}
