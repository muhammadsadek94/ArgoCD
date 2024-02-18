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

// The controllers live in src/Domains/Enterprise/Http/Controllers
Route::group(['namespace' => 'Admin', 'middleware' => 'admin.auth', 'prefix' => Constants::ADMIN_BASE_URL], function () {
    Route::resource('/enterprise', 'EnterpriseController');
    Route::patch('/enterprise/action/{user}/password', 'EnterpriseController@patchUpdatePassword');
    Route::patch('/enterprise/action/{user}/UpdateLearnPath', 'EnterpriseController@UpdateLearnPath');
    Route::patch('/enterprise/action/{user}/license', 'EnterpriseController@updateLicense');
    Route::patch('/enterprise/action/{user}/subaccount-license', 'EnterpriseController@patchUpdateSubaccountLicenses');
    Route::post('/enterprise/action/upload-profile-picture', 'EnterpriseController@postUploadPicture');
    Route::resource('/enterprise/actions/learnPath', 'EnterpriseLearnPathController');

    Route::resource('/enterprise-learn-path', 'EnterpriseLearnPathController');

});

