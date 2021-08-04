<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SignUpActivate;
use App\Http\Controllers\Controller;


class AUthController extends Controller
{
    //page to redirect to after successful reigstration
    protected $redirectToRegister = '/home';

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function showVerifyForm($errors = null)
    {
        return view('auth.verify')->with(compact('errors'));
    }

    public function register(Request $request)
    {
        $validate =  Validator::make($request->all(),[
            'first_name' => 'required|string|min:2',
            'last_name' => 'required|string|min:2',
            'email'=>'required|unique:users',
            'password'=>'required|min:5|confirmed',
        ]);

        if($validate->fails())
        {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->activation_token = str_random(8);

        $user->save();

        $user->notify(new SignUpActivate($user));

        $this->guard()->login($user);

        return view('auth.verify')->with(compact('user'));

    }



    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function registerVerifyViaMail($token)
    {
        $user = User::where('activation_token', $token)->first();
        if(null === $user)
        {
            return $this->showVerifyForm(collect(['token'=>'invalid token given']));
        }
        if($user->active){
            return $this->redirectTo($user);
        }
        return $this->verifyEmail($user);

    }

    public function registerVerify(Request $request)
    {
        $user = Auth::user();
        if($user->active){
            return $this->redirectTo($user);
        }
        $validate = Validator::make($request->all(),[
            'activation_token'=>['required',
                    Rule::exists('users')->where(function($query) use ($user){
                        return $query->where('id', $user->id);
                    })]
        ]);

        if($validate->fails()){
            return $this->showVerifyForm(collect(['token'=>'invalid token given']));
        }

        return $this->verifyEmail($user);
    }

    public function verifyEmail(User $user)
    {
        $user->active = true;
        $user->markEmailAsVerified();
        $user->activation_token = '';
        $user->save();

        return $this->redirectTo($user);
    }

    public function resendVerify()
    {
        $newToken = str_random(8);
        $user  = Auth::user();
        $user->activation_token = $newToken;
        $user->save();
        $user->notify(new SignUpActivate($user));
        return view('auth.verify')->with(compact('user'));
    }

    public function redirectTo(User $user)
    {
        if($user->role === 2)
        {
            return redirect('/admin');
        }else if($user->role === 1){
            return redirect('/student');
        }else if($user->role === 3){
            return redirect('/instructor');
        }
    }

    public function guard()
    {
        return Auth::guard();
    }

}
