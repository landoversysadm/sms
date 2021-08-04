<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function getDashboard()
    {
        if(Auth::user()->role === 2 || Auth::user()->role === 4)
        {
            return redirect()->route('admin_dashboard');
        }else if(Auth::user()->role === 1){
            return redirect()->route('student_dashboard');
        }else if(Auth::user()->role === 3){
            return redirect()->route('instructor_dashboard');
        }
    }
}
