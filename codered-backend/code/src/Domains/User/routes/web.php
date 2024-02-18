<?php

/*
|--------------------------------------------------------------------------
| Service - Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for this service.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Domains\Course\Models\Lesson;
use App\Domains\User\Models\Instructor\Payout;

Route::group(['namespace' => 'Admin', 'middleware' => 'admin.auth', 'prefix' => Constants::ADMIN_BASE_URL], function() {
    Route::resource('/user', 'UserController');
    Route::post('/user/action/upload-profile-picture', 'UserController@postUploadPicture');
    Route::patch('/user/action/{user}/phone', 'UserController@patchPhone');
    Route::patch('/user/action/{user}/password', 'UserController@patchUpdatePassword');
    Route::post('/user/action/{user}/subscription', 'UserController@addSubscriptionToUser');
    Route::post('/user/action/{user}/enroll-microdegree', 'UserController@enrollInMicrodegree');

    Route::post('/user/action/{user}/generate-certificate', 'UserController@generateCertificate');
    Route::get('/user/action/generate-certificate-number', 'UserController@generateCertificateNunber');
    Route::get('/user/action/{country_id}/cities', 'UserController@getCities');
    Route::get('/user/action/{user}/loggedin', 'UserController@loggedInAsUser');
    Route::get('/actions/microdegree/{course}/user/{user_id}/{enrollment}', 'UserController@deleteSubscription');
    Route::get('/actions/course-certificate/{completed_course}', 'UserController@deleteCertificate');

    Route::resource('/instructor', 'InstructorController');
    Route::post('/instructor/action/upload-profile-picture', 'InstructorController@postUploadPicture');
    Route::patch('/instructor/action/{user}/phone', 'InstructorController@patchPhone');
    Route::patch('/instructor/action/{user}/password', 'InstructorController@patchUpdatePassword');
    Route::get('/instructor/action/{user}/generate-password', 'InstructorController@generateAndSendPassword');
    Route::get('/instructor/action/{country_id}/cities', 'InstructorController@getCities');
    Route::get('/instructor/action/{user}/loggedin', 'InstructorController@loggedInAsUser');

     Route::resource('/user-tag', 'UserTagController');
      Route::get('/user-tag/actions/get-tags', 'UserTagController@searchTagsByCategory');
    Route::resource('/goal', 'GoalController');
   // Route::resource('/subscription-cancellation', 'SubscriptionCancellationController');
   // Route::get('/subscription-cancellation/actions/{id}/cancel', 'SubscriptionCancellationController@cancel');

   Route::get('/payout/export-reports', 'PayoutController@showReport');
   Route::post('/payout/export-reports', 'PayoutController@exportExcel');
    Route::resource('/payout', 'PayoutController');
    Route::get('/payout/actions/{id}/approve', 'PayoutController@approve');
    Route::get('/payout/actions/{id}/disapprove', 'PayoutController@disapprove');
    Route::get('/payout/actions/{id}/paid', 'PayoutController@paid');
    Route::get('/payout/actions/test', 'PayoutController@test');
    Route::post('/payout/actions/export', 'PayoutController@export');
    Route::post('/payout/actions/royalties', 'PayoutController@addRoyalties');

    Route::get('/payout/actions/{id}/export', 'PayoutController@exportPDF');

    Route::resource('/user/actions/bundle', 'UserSubscriptionController');
    Route::get('/re-assign-assignment/{userId}/{courseId}', 'UserController@reAssignAssignment')->name('re-assign.assignment');

    Route::resource('default-image','DefaultImageController');

});
//
//Route::get("/go", function(){
//  $p = Payout::first();
//  dd($p->course ? $p->course->name : "");
//});
