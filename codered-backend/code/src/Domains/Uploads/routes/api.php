<?php

/*
|--------------------------------------------------------------------------
| Service - API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for this service.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Prefix: /api/v1/uploads
Route::group(['prefix' => 'v1/uploads'], function() {

    Route::group(['namespace' => "Api\V1"], function (){
        Route::post("create", "UploadsController@store");
        Route::post("private", "UploadsController@storePrivate");
    });

});

Route::group(['prefix' => 'v2/uploads'], function() {

    Route::group(['namespace' => "Api\V1"], function (){
        Route::post("create", "UploadsController@store");
        Route::post("private", "UploadsController@storePrivate");
    });

});
