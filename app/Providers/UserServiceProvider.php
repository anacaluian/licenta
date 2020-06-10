<?php

namespace App\Providers;

use App\User;
use http\Env\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider
{
    protected $userModel;

    public function __construct(User $user)
    {
        $this->userModel = $user;
    }

    public function index(){

        $users = $this->userModel::all();
        return response()->json(
            [
                'status' => 'success',
                'users' => $users->toArray()
            ], 200);
    }

    public function members(){

        $members = $this->userModel::where('role',2)->get();
        return response()->json(
            [
                'status' => 'success',
                'members' => $members->toArray()
            ], 200);
    }

    public function membersEdit(array $data){

        $members = $this->userModel::find($data['id']);
        $members->first_name = $data['first_name'];
        $members->last_name = $data['last_name'];
        $members->email = $data['email'];
        if ($members->save()){
            return response()->json(
                [
                    'status' => 'success',
                ], 200);
        }
        return response()->json('error',500);
    }

    public function membersDelete(array $data){

        $members = $this->userModel::find($data['id']);
        if ($members->delete()){
            return response()->json(
                [
                    'status' => 'success',
                ], 200);
        }
        return response()->json('error',500);
    }



    public function clients(){
        $clients = $this->userModel::where('role',3)->get();
        return response()->json(
            [
                'status' => 'success',
                'clients' => $clients->toArray()
            ], 200);
    }

    public function clientsEdit(array $data){

        $clients = $this->userModel::find($data['id']);
        $clients->first_name = $data['first_name'];
        $clients->last_name = $data['last_name'];
        $clients->email = $data['email'];
        $clients->phone = $data['phone'];
        if ($clients->save()){
            return response()->json(
                [
                    'status' => 'success',
                ], 200);
        }
        return response()->json('error',500);
    }

    public function clientsDelete(array $data){

        $clients = $this->userModel::find($data['id']);
        if ($clients->delete()){
            return response()->json(
                [
                    'status' => 'success',
                ], 200);
        }
        return response()->json('error',500);
    }

    public function show($id){

        $user =  $this->userModel::find($id);
        return response()->json(
            [
                'status' => 'success',
                'user' => $user->toArray()
            ], 200);
    }

    public function profilePhoto(array $data){
        $path = Storage::disk('public')->putFile('profile', $data['file']);
        $profile = $this->userModel->where('id',$data['user_id'])->update([
            'profile_photo' =>  Storage::url($path)
        ]);
        if ($profile){
            return response()->json('success',200);
        }
        return response()->json('error',500);
    }

    public function update(array $data){
       $update = $this->userModel->where('id',$data['user_id'])->update([
          'first_name' => $data['first_name'],
          'last_name' => $data['last_name'],
          'email' => $data['email']
       ]);
       if ($update){
           return response()->json('success',200);
       }else{
           return response()->json('error',500);
       }
    }

}
