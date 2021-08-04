<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\SystemSetting;

Route::get('/', function () {

    return view('welcome')->with("App", SystemSetting::all()->first());
})->name('home');


Route::get('upload', function(){
    return view('upload');
});
Route::post('upload', 'UploadController@upload');
//this route is performing authentication
Route::get('register', 'AuthController@showRegisterForm')->name('register');
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');

Route::post('register', 'AuthController@register');
Route::post('login', 'Auth\LoginController@login');

Route::post('password/email ', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');




Route::group(['middleware'=> 'auth:web'], function (){
    Route::get('user', 'AuthController@user');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('register/verify/{token}','AuthController@registerVerifyViaMail');
    Route::post('register/verify','AuthController@registerVerify');
    Route::get('register/verify','AuthController@showVerifyForm')->name('verify');
    Route::get('register/verify','AuthController@showVerifyForm')->name('verification.notice');
    Route::get('register/resend_verify','AuthController@resendVerify')->name('resend_verify');
});



Route::group(['middleware'=> ['auth:web','verified']], function(){
    Route::get('create/profile', 'StudentController@showCreateForm')->name('create-profile');
    Route::post('create/profile', 'StudentController@store');
    Route::post('enroll/{courseId}', 'EnrollmentController@store')->name('enroll');

    //Student specific routes
    Route::group(['middleware'=>'isStudent'], function(){
        Route::get('student', 'StudentController@home')->name('student_dashboard');
        Route::get('student/{path}', "StudentController@home")->where('path', '[\/\w\.-]*');
    });

    //Admin specific routes
    Route::group(['middleware'=>'isAdmin'], function(){
        Route::get('admin', 'AdminController@home')->name('admin_dashboard');
        Route::get('admin/{path}', "AdminController@home")->where('path', '[\/\w\.-]*');
    });

    //Instructor specific routes
    Route::group(['middleware'=>'isInstructor'], function(){
        Route::get('instructor', 'InstructorController@home')->name('instructor_dashboard');
        Route::get('instructor/{path}', "InstructorController@home")->where('path', '[\/\w\.-]*');
    });

    Route::get('dashboard', 'HomeController@getDashboard')->name('dashboard');
    Route::get('home', 'HomeController@getDashboard');
    Route::get('/enroll/{courseId}', 'EnrollmentController@index')->name('enroll');
});


