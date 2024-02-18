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

// Prefix: /api/v1/contact_us
Route::group(['prefix' => 'v1/user/app/contact-us', "namespace" => "Api\V1\User"], function() {

    Route::get("/subjects", "ContactUsController@getSubjects");
    Route::post("/save", "ContactUsController@store");

});

////////////////////////////////V2///////////////////////////////////

Route::group(['prefix' => 'v2/user/app/contact-us', "namespace" => "Api\V2\User"], function() {

    Route::get("/subjects", "ContactUsController@getSubjects");
    Route::post("/save", "ContactUsController@store")->middleware('ReCAPTCHA');

});
