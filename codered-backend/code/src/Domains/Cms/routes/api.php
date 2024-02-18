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

// Prefix: /api/v1/cms
Route::group(['prefix' => 'v1/cms'], function() {

    // The controllers live in src/Domains/Cms/Http/Controllers
    // Route::resource('/cms', 'CmsController');



    Route::group(['middleware'=>'auth:api'], function() {

        Route::group(['prefix'=>'app'], function() {

        });

    });

    Route::group(['prefix'=>'app'], function() {

    });


});
