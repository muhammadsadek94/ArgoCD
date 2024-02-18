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

// Prefix: /api/v1/notification
Route::group(['prefix' => 'v1/app', "middleware" => "auth:api", "namespace" => "Api\V1"], function() {
    Route::get("notifications", "NotificationController@index");
    Route::patch("notifications", "NotificationController@update");
});
