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


Route::group(['prefix' => Constants::ADMIN_BASE_URL], function() {
    Route::group(["namespace" => "Admin\Auth"], function() {
        Route::get('login', 'LoginController@showLoginForm')->name("admin.login");
        Route::post('login', 'LoginController@login');

        Route::get('/code', 'LoginController@showCodeForm')->name('admin.code_page');
        Route::post('/code', 'LoginController@storeCodeForm')->name('admin.code');

        Route::post('logout', 'LoginController@logout')->name("admin.logout");
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');
//        Route::get('password/confirm', 'ConfirmPasswordController@showConfirmForm')->name('password.confirm');
//        Route::post('password/confirm', 'ConfirmPasswordController@confirm');

    });

    Route::group(["middleware" => "admin.auth", 'namespace' => 'Admin'], function() {
        Route::get('/', 'Auth\LoginController@dashboard');
        Route::get('/dashboard', 'Auth\LoginController@dashboard');

        Route::group(['prefix' => 'admin'], function() {
            Route::get('/my-account/update-profile', 'ProfileController@getIndex');
            Route::post('/my-account/update-profile', 'ProfileController@postIndex');
            Route::post('/my-account/update-profile/actions/upload-profile-picture', 'ProfileController@postUploadPicture');
        });

        Route::resource('/admin', 'AdminController');
        Route::post('/admin/action/upload-profile-picture', 'AdminController@postUploadPicture');
        Route::patch('/admin/action/{admin}/password', 'AdminController@patchUpdatePassword');

        Route::resource('/role', 'RoleController');



    });
});



