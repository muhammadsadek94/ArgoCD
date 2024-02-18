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


// Prefix: /api/v1/blog
Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function() {
    Route::group(['namespace' => 'User'], function() {
        Route::group(['prefix' => 'blog'], function() {
            //api/v1/blog/homeblog
            Route::get('/homeblog', 'BlogController@index');
            Route::get('/{article_id}', 'BlogController@show');
            Route::post('/{article_id}/action/download-attachment', 'BlogController@requestDownloadAttachment');
            Route::post('/{article_id}/action/likes', 'BlogController@storeLikes')->middleware('auth:api');
        });
    });
});
