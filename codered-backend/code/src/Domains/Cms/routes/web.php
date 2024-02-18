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

// The controllers live in src/Domains/Cms/Http/Controllers
Route::group(['namespace' => 'Admin', 'middleware'=>'admin.auth', 'prefix' => Constants::ADMIN_BASE_URL], function() {

        Route::resource('slider', 'SliderController');
        Route::post('/slider/actions/upload-image', 'SliderController@postUploadPicture');

        Route::resource('brand', 'BrandController');
        Route::post('/brand/actions/upload-image', 'BrandController@postUploadPicture');
});

