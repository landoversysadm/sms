<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BankDetailController;
use App\Http\Controllers\AssessmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Status for enrollment --'approved' 'pending' 'completed
//'validated' status for payment  -- 0 for pending validation, 1 for declined validation, 2 for accepted validation
// role 1- for student as the first class user of the system, role 2-  for Super Admin, role 3- for instructor
//role 4 - 10 for minimal access administrators
Route::prefix('auth')->group(function () {

  //this route is performing authentication
  Route::post('register', 'AuthController@register');
  Route::post('login', 'AuthController@login');

  //this route is to refresh autherntication token
  Route::get('refresh', 'AuthController@refresh');

  Route::get('signup/activate/{token}', 'AuthController@signupActivate');

  Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user', 'AuthController@user');
    Route::post('logout', 'AuthController@logout');
  });
});

/*
* routes define here do not require authentication, they will be mostly used on the homepage,
* as such login is not required
*/
Route::get('courses', 'CourseController@index');
Route::get('admin/students', 'StudentController@index');
/*
* this (proposed) route is to get the list of courses to be display on
* the homepage wuile exempting the course enrolled to by an authenticated user,
* its currently handled from the client side with JS
*/
// Route::get('listCourses', 'CourseController@listCourse');
Route::get('faculties', 'FacultyController@index');
Route::get('courses/banner/{url}', 'CourseController@getImageUrl');


/**
 * Route defined here requires Authentication,either Admin, Instructor or Student
 *
 */
Route::get('system', 'SystemController@index');
Route::group(['middleware' => ['auth:api', 'verified']], function () {
  Route::get('users', 'UserController@index')->middleware('isAdmin');
  Route::get('users/{id}', 'UserController@show')->middleware('isAdminOrSelf');
  Route::get('user/', 'UserController@currentUser');



  Route::group(['middleware' => ['isStudent']], function () {
    Route::get('user/student', 'UserController@currentUser');
    Route::post('user/student/apply/course/{courseId}', 'StudentController@applyCourse');
    Route::post('user/student/pay/course/{courseId}', 'PaymentController@payCourse');
    Route::get('user/student/payments', 'PaymentController@index');
    Route::get('user/student/myPayments', 'PaymentController@studentPayments');
    Route::post('user/student/payments/{paymentId}', 'PaymentController@repayCourse');
    Route::get('user/{id}/student/profile', 'StudentController@profile');
    Route::post('user/{id}/student/profile', 'StudentController@store');
    Route::patch('user/student/profile', 'StudentController@update');
    Route::patch('user/student/profile/picture', 'StudentController@updateProfilePicture');
    Route::delete('user/{id}/student/profile', 'StudentController@destroy');
    Route::get('user/student/enrollment/history', 'StudentController@enrollmentHistory');
    Route::post('user/{id}/student/courses', 'StudentController@getCourses');
    Route::post('user/student/course/{courseId}', 'CourseController@getCourse');
    Route::post('user/student/course/{courseId}/evaluate', 'CourseController@evaluateCourse');
    Route::get('user/{id}/student/course/{courseId}/evaluate', 'CourseController@getEvaluation');
    // Route::get('user/{id}/student/course/{courseId}/assessment/test', 'CourseController@getTestAssessment');
    // Route::get('user/{id}/student/course/{courseId}/assessment/exam', 'CourseController@getExamAssessment');
    // Route::get('user/{id}/student/course/{courseId}/assessment/assignment', 'CourseController@getAssignmentAssessment');
    Route::get('user/student/activeCourses', 'CoursesStudentsController@studentActiveCourse');
    Route::get('user/student/enrollment', 'EnrollmentController@getMyEnrollments');
    Route::get('user/student/notification', 'StudentController@notifications');
    Route::patch('user/student/notification', 'NotificationController@updateNotification');
    Route::get('user/student/assessment/{courseStudentId}', 'AssessmentController@getMyAssessment');
  });

  Route::group(['middleware' => ['isInstructor']], function () {
    Route::get('instructor/profile', 'InstructorController@profile');
    Route::get('instructor/students/{courseId}/{sessionId}', 'InstructorController@students');

    Route::get('instructor/assessment/{courseId}/{sessionId}', 'AssessmentController@getAssessmentForCourse');
    Route::post('instructor/assessment/save', 'AssessmentController@saveAssessment');

    Route::patch('instructor/updatePassword', 'InstructorController@updatePassword');
    Route::patch('instructor/updateInfo', 'InstructorController@update');

    Route::post('instructor/customNotifications', 'NotificationController@customNotificationFromAdmin');
    Route::get('instructor/customNotifications', 'NotificationController@getCustomNotification');
    Route::delete('instructor/customNotifications', 'NotificationController@deleteCustomNotification');

    // Route::post('instructor/material/{courseId}', 'MaterialController@store');
    // Route::delete('material/{materialId}', 'MaterialController@destroy');
  });

  Route::group(['middleware' => ['isAdmin']], function () {
    Route::delete('material/{materialId}', 'MaterialController@destroy');
    // Route::get('material/download/{file}', 'MaterialController@downloadFile');

    Route::get('admin/courses', 'CourseController@index');
    Route::get('admin/courseReport', 'CourseController@courseReport');

    Route::get('admin/faculties', 'FacultyController@index');

    Route::get('admin/courseStudent', 'CoursesStudentsController@index');
    Route::get('admin/courseStudent/distinct', 'CoursesStudentsController@distinctStudent');

    Route::post('admin/material/{courseId}', 'MaterialController@store');

    Route::post('admin/faculty', 'FacultyController@store');
    Route::delete('admin/faculty/{facultyId}', 'FacultyController@destroy');
    Route::get('admin/student/{studentId}', 'StudentController@getStudent');
    Route::get('admin/students/{courseId}/{sessionId}', 'CoursesStudentsController@sessionStudent');
    Route::patch('admin/faculty/{facultyId}', 'FacultyController@update');
    Route::get('admin/faculty/{facultyId}/courses', 'FacultyController@allCourse');
    Route::post('admin/faculty/{facultyId}/course', 'FacultyController@addCourse');
    Route::delete('admin/course/{courseId}', 'CourseController@destroy');
    Route::patch('admin/faculty/{facultyId}/course/{courseId}', 'FacultyController@updateCourse');
    Route::patch('admin/faculty/{facultyId}/course/{courseId}/banner', 'FacultyController@updateCourseBanner');

    Route::post('admin/faculty/session/{facultyId}/course/{courseId}/', 'FacultyController@startCourseSession');



    Route::get('admin/enrollment', 'EnrollmentController@index');
    Route::get('admin/enrollment/status/{status}', 'EnrollmentController@getEnrollment');
    Route::get('admin/enrollment/{enrollmentId}', 'EnrollmentController@singleEnrollment');
    Route::patch('admin/enrollment/{enrollmentId}', 'EnrollmentController@update');

    Route::get('admin/payments', 'PaymentController@index');
    Route::patch('admin/payments/{paymentId}', 'PaymentController@update');

    Route::get('admin/instructors',  'InstructorController@index');
    Route::post('admin/instructors', 'InstructorController@store');
    Route::patch('admin/instructor/{instructorId}', 'InstructorController@update');
    Route::post('admin/instructor/{instructorId}', 'InstructorController@destroy');

    Route::get('admin/accounts', 'AdminController@index');
    Route::post('admin/account', 'AdminController@store');
    Route::patch('admin/profile', 'AdminController@update');
    Route::get('admin/profile', 'AdminController@getProfile');
    Route::delete('admin/account/{adminId}', 'AdminController@destroy');

    Route::get('admin/bankDetails', 'BankDetailController@index');
    Route::post('admin/bankDetails', 'BankDetailController@store');
    Route::patch('admin/bankDetails/{bankDetailsId}', 'BankDetailController@update');

    Route::get('admin/customNotifications', 'NotificationController@getCustomNotification');
    Route::delete('admin/customNotifications', 'NotificationController@deleteCustomNotification');
    Route::post('admin/customNotifications', 'NotificationController@customNotificationFromAdmin');

    Route::post('admin/assessment/save', 'AssessmentController@saveAssessment');
    Route::get('admin/assessment/{courseId}/{sessionId}', 'AssessmentController@getAssessmentForCourse');

    Route::post('admin/activate', 'UserController@activateUser');
    Route::post('admin/deactivate', 'UserController@deactivateUser');

    Route::get('admin/requirements', 'RequirementController@index');
    Route::post('admin/requirements', 'RequirementController@store');
    Route::patch('admin/requirements/{requirementId}', 'RequirementController@patch');
    Route::delete('admin/requirements/{requirementId}', 'RequirementController@destroy');

    Route::patch('admin/system', 'SystemController@updateAppSettings');
    Route::patch('admin/system/banner', 'SystemController@updateAppBanner');
  });
});