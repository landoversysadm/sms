<?php

namespace App\Http\Controllers\Api;

use App\Admin;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use DateTime;

class AdminController extends Controller
{

    use ResetsPasswords;


    public $respond;

    public function __construct(ResponseHelper $response)
    {
        $this->middleware('isAdmin');
        $this->respond = $response;
    }

    public function index()
    {
        $admins = Admin::all();
        return $this->respond->withSuccess((string)Response::HTTP_OK,$admins);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|alpha_num',
            'role' => ['required',Rule::in([2,4])],
        ]);
       $user =  User::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'midlle_name' => $request->middleName,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'email_verified_at'=> (new DateTime('now')),
            'activation_token'=> '',
        ]);
        $user->active = 1;
        $user->save();

        return $this->respond->withSuccess((string)Response::HTTP_CREATED,$user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'section' => ['required', Rule::in(['personalInfo', 'password'])]
        ]);

        return $this->{$request->section.'Update'}($request);
    }

    public function personalInfoUpdate(Request $request)
    {
        $this->validate($request, [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
        ]);

        $user = Auth::user()->fill([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'midlle_name' => $request->middleName,
            'email' => $request->email
        ])->save();

        return $this->respond->withSuccess(Response::HTTP_ACCEPTED, $data = $user);

    }

    public function passwordUpdate(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'password' => ['bail','required',
                               function($attribute, $value, $fail) use ($user){
                                    if(!Hash::check($value, $user->makeVisible('password')->password)){
                                        $fail($attribute.' is not the the current password');
                                    }
                               }
                            ],
            'newPassword' => 'required|min:6',
            'confirmPassword' => 'required|same:newPassword'

        ]);

        Auth::user()->fill([
             'password' => Hash::make($request->newPassword)
             ])->save();


             return $this->respond->withSuccess((string)Response::HTTP_ACCEPTED, $data = $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $admin = Admin::where('id', $request->adminId)->firstOrFail();
        if($admin->id !== $request->adminId){
            $admin->delete();
            return $this->respond->withSuccess((string)Response::HTTP_ACCEPTED, []);
        }
        return $this->respond->withError((string)Response::HTTP_FORBIDDEN, []);

    }

    /**
     * Remove the specified admin profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getProfile(Request $request)
    {
        $user = Auth::user();
        return $this->respond->withSuccess((string)Response::HTTP_OK, $user);
    }
}
