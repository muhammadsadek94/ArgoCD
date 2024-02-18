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
Route::group(["prefix" => "admin", "namespace" => "Admin", "middleware" => "auth:admin"], function () {
    // Route::resource("contact-us/contact-us-subject", "ContactUsSubjectController");

    Route::resource('contact-us', 'ContactUsController')->only(['index', 'show', 'destroy']);
    Route::post('contact-us/{contact_us}', 'ContactUsController@reply');

});



Route::group(['prefix' => 'admin', "namespace" => "Admin", 'middleware' => 'admin.auth'], function (){
    Route::resource('contact-us', 'ContactUsController')->only(['index', 'show', 'destroy']);
    // Route::resource('contact-us-subject', 'ContactUsSubjectController');
    Route::post('contact-us/{contact_us}', 'ContactUsController@reply');
});
