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

// Prefix: /api/v1/bundles
Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function() {
    Route::group(['namespace' => 'User'], function() {
        Route::group(['prefix' => 'bundles'], function() {
            //api/v1/bundles/
            Route::get('/action/home', 'BundlesController@index');

            Route::get('/{bundle_id}', 'BundlesController@show');
      
        });
    });
});


Route::group(['prefix' => 'v2', 'namespace' => 'Api\V2'], function() {
    Route::group(['namespace' => 'User'], function() {
        Route::group(['prefix' => 'bundles'], function() {
            Route::get('/{bundle_id}', 'BundlesController@show');
        });
    });
});