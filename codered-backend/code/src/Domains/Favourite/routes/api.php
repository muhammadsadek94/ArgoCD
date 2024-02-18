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

// Prefix: /api/v1/user/app/favourite
// Route::group(['prefix' => 'v1/user/app', 'middleware'=>'auth:api', 'namespace'=>'Api\V1\User'], function() {
//     Route::resource('favourite', 'FavouriteController', ['expected' => 'destroy']);
//     Route::delete('favourite', 'FavouriteController@destroy');
// });

Route::group(['prefix' => 'v2/user/app', 'middleware'=>'auth:api', 'namespace'=>'Api\V2\User'], function() {
    Route::resource('favourite', 'FavouriteController', ['expected' => 'destroy']);
    Route::delete('favourite', 'FavouriteController@destroy');
});
