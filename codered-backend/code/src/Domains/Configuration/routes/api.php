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

// Prefix: /api/v1/configuration
Route::group(['prefix' => 'v1/configuration'], function() {

    // The controllers live in src/Domains/Configuration/Http/Controllers
    // Route::get('/', 'UserController@index');

//    Route::get('/', 'Api\ConfigurationController@index');
   // Route::get('/packages', 'Api\ConfigurationController@getPackages')->middleware(['auth:api']);

   // Route::get('/packages', '\App\Domains\Payments\Http\Controllers\Api\V1\User\PackageSubscriptionController@getPackages')->middleware(['auth:api']);
 
});


