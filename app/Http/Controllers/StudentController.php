<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    //

    public function application()
    {
        return view('apply');
    }

    public function home()
    {
        return view('student/home');
    }

    public function showCreateForm()
    {
        return view('auth/create_profile');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = Auth::user()->id;
        $studentExist = Student::where('user_id', $id)->get();
        if($studentExist->isNotEmpty())
        {
            return redirect()->back()->withErrors(['student profile exists already'])->withInput();
        }
        $validate = Validator::make($request->all(), [
            'sex'=>'required',
            'profile_picture'=>'required|image',
            'date_of_birth'=>'required|date'
        ]);

        if($validate->fails())
        {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        if($request->profile_picture->isValid())
        {
            $profilePath = $request->profile_picture->store('/public/profile_pictures');
        }

        $student = new Student();
        $student->user_id = $id;
        $student->profile_picture_url = $profilePath;
        $student->sex = $request->sex;
        $student->date_of_birth = $request->date_of_birth;
        $student->save();

        return redirect('/student');

    }
}
