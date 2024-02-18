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

// Prefix: /api/v1/app/
Route::group(['prefix' => 'v1/user/app'], function() {
    // Prefix: /api/v1/app/faq
    Route::group(['prefix'=>'faq', "namespace" => "Api\V1\User"], function() {
        Route::get("/", "FaqController@index");
    });



});

///////////////////////////////////////////////V2///////////////////////////////////////////

Route::group(['prefix' => 'v2/user/app'], function() {
    Route::group(['prefix'=>'faq', "namespace" => "Api\V2\User"], function() {
        Route::get("/", "FaqController@index");
    });
});
