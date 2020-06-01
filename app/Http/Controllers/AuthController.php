<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3|confirmed',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $user = new User;
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->save();
        return response()->json(['status' => 'success'], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ($token = $this->guard()->attempt($credentials)) {
            return response()->json(['status' => 'success'], 200)->header('Authorization', $token);
        }
        return response()->json(['error' => 'login_error'], 401);
    }

    private function guard()
    {
        return Auth::guard();
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    public function user(Request $request)
    {

        $user = User::where('id',Auth::user()->id)->get();

        $userobj = new \stdClass;
        $userobj->id = $user->pluck('id')->first();
        $userobj->first_name = $user->pluck('first_name')->first();
        $userobj->last_name = $user->pluck('last_name')->first();
        $userobj->email = $user->pluck('email')->first();
        $userobj->roles =[$user->pluck('role')->first()];

        return response()->json([
            'status' => 'success',
            'data' => $userobj
        ]);
    }

    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'successs'], 200)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], 401);
    }
    public function reset(Request $request){
        dd( $request->get('email'));
        if ($request->has('email')){
            $user = User::where('email', $request->get('email'))->first();
            if ($user === null) {
                return response()->json(['error' => 'No email found'], 401);
            }else
            {
                $new_pass = Str::random(8);
                User::where('email', $request->get('email'))->update([
                    'password' =>  bcrypt($new_pass)
                ]);
                Mail::to($request->get('email'))->send(new ForgotPassword($new_pass));
                return response()->json(['success' => 'Password changed'], 200);
            }
        }
        return response()->json(['error' => 'No email found'], 401);
    }
}
