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

// Prefix: /api/v1/partner
Route::group(['prefix' => 'v1/partner', 'namespace' => 'Api\V1'], function() {
    Route::get('course/filtertion-data', 'CourseController@GetFilterationData');

    Route::apiResource('course', 'CourseController')->only('index', 'show');


});
