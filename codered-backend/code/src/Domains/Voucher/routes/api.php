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

// Prefix: /api/v1
Route::group(['prefix' => 'v1'], function() {


    Route::group(['middleware'=>'auth:api'], function() {
        Route::post('user/profile/voucher', 'Api\V1\User\VoucherController@postVoucher');

    });

    Route::group(['prefix'=>'app'], function() {

    });


});


/////////////////////////////// V2 //////////////////////////////////
Route::group(['prefix' => 'v2'], function() {

    Route::group(['middleware'=>['auth:api', 'throttle:5,60']], function() {
        Route::post('user/profile/voucher', 'Api\V2\User\VoucherController@postVoucher');
    });
});
