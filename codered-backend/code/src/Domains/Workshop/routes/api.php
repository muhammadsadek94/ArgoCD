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

// Prefix: /api/v1/workshop
Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function() {
    Route::group(['namespace' => 'User'], function() {
        Route::group(['prefix' => 'workshop'], function() {
            //api/v1/workshop/
            Route::get('/', 'WorkshopController@index');

            Route::get('/{id}', 'WorkshopController@show');
      
        });
    });
});

Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function() {
    Route::group(['namespace' => 'User'], function() {
        Route::group(['prefix' => 'workshop'], function() {
            //api/v1/workshop/
            Route::get('/', 'WorkshopController@index');

            Route::get('/{id}', 'WorkshopController@show');
      
        });
    });
});

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////V2/V2/V2/V2/V2/V2/V2/V2//V2/V2/V2/V2//////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

Route::group(['prefix' => 'v2', 'namespace' => 'Api\V2'], function() {
    Route::group(['namespace' => 'User'], function() {
        Route::group(['prefix' => 'workshop'], function() {
            Route::get('/', 'WorkshopController@index');
            Route::get('/{id}', 'WorkshopController@show');
        });
    });
});