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

// The controllers live in src/Domains/Bundles/Http/Controllers
Route::group(['namespace' => 'Admin', 'middleware'=>'admin.auth', 'prefix' => Constants::ADMIN_BASE_URL], function() {
    # Route::resource('/bundles', 'BundlesController');
    Route::resource('/course-bundle', 'CourseBundleController');
    Route::post('/course-bundle/action/upload-image', 'CourseBundleController@postUploadPicture');

    Route::resource('/promo-code', 'PromoCodeController');



    



   

});





