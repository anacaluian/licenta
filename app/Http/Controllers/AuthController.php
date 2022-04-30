<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPassword;
use App\Mail\Register;
use App\Providers\AuthServiceProvider;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AuthController extends Controller
{


    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required',
            'phone' => 'regex:/(0)[0-9]{9}/'
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
      $user->username = strtolower(str_replace(' ', '', $user->first_name)) . '.' . strtolower(str_replace(' ', '', $user->last_name));
      $user->email = $request->get('email');
      $user->role = $request->get('role');
      if ( $request->get('phone')){
          $user->phone = $request->get('phone');
      }
      $password = Str::random(10);
      $user->password = bcrypt($password);
      if ( $user->save()){
          Mail::to($request->get('email'))->send(new Register( $request->get('email'),$password));
          return response()->json(['status' => 'success'], 200);
      }
      return response()->json(['status' => 'error'], 500);
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

    public function user()
    {
        $user = User::where('id',Auth::user()->id)->get();

        $userobj = new \stdClass;
        $userobj->id = $user->pluck('id')->first();
        $userobj->first_name = $user->pluck('first_name')->first();
        $userobj->last_name = $user->pluck('last_name')->first();
        $userobj->username = $user->pluck('username')->first();
        $userobj->profile_photo = $user->pluck('profile_photo')->first();
        $userobj->email = $user->pluck('email')->first();
        $userobj->roles =[$user->pluck('role')->first()];

        return response()->json([
            'status' => 'success',
            'data' => $userobj
        ]);
    }

    public function take(Request $request)
    {
        $user = User::where('username', $request->get('user'))->get();

        if ($user) {
            if ($token = $this->guard()->tokenById($user->pluck('id')->first())) {
                return response()
                    ->json(['status' => 'successs'], 200)
                    ->header('Authorization', $token);
            }
        }
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

        $v = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }


        if ($request->has('email')){
            $user = User::where('email', $request->get('email'))->first();
            if ($user === null) {
                return response()->json(['error' => 'No email found'], 401);
            }else
            {
                $new_pass = Str::random(10);
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
