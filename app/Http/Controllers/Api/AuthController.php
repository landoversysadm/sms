<?php

namespace App\Http\Controllers\Api;

use App\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SignUpActivate;
use App\Http\Controllers\Controller;


class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validate =  Validator::make($request->all(),[
            'first_name' => 'required|string|min:5',
            'last_name' => 'required|string|min:5',
            'email'=>'required|unique:users',
            'password'=>'required|min:5|confirmed',
        ]);

        if($validate->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $validate->errors()
            ], 422);
        }

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->activation_token = str_random(60);

        $user->save();

        $user->notify(new SignUpActivate($user));

        return response()->json(['status'=>'success','message'=>'Account created successfully'],
        200);
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'email'=> 'required|email',
            'password'=> 'required|min:5'
        ]);

        if($validate->fails())
        {
            return response()->json([
                'status'=>'error',
                'errors'=>$validate->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials))
        {
            $user = Auth::user();
            if($user->active)
            {
                return response()->json(['status'=>'success', 'message'=>'authentication sucessful'],
                    200)->header('Authorization', $token);
            }else
            {
                return response()->json(['status'=>'error','error' => 'login_failed', 'message'=> 'account not activated'], 401);
            }

        }

        return response()->json(['status'=>'error','error' => 'login_failed', 'message'=>'invalid login credentials'],
        401);
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function refresh()
    {
        if($token = $this->guard()->refresh())
        {
            return response()
                ->json(['status'=>'success'], 200)
                ->header('Authorization', $token);
        }

        return reposnse()->json(['status'=>'error','error'=>'refresh_token_error']
                ,401);
    }

    public function signUpActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if(!$user)
        {
            return response()->json([
                'message' => 'Invalid Activation token'
            ], 404);
        }

        $user->active = true;
        $user->markEmailAsVerified();
        $user->activation_token = '';
        $user->save();

        return response()->json([
            'status'=>'success', 'message' => 'Activation successful'
        ], 201);

    }

    public function guard()
    {
        return Auth::guard();
    }

}
