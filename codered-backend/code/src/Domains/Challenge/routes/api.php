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

// Prefix: /api/V2/challenge
Route::group(['prefix' => 'v2/challenge'], function () {
    Route::group(['namespace' => 'Api\V2', 'middleware' => 'auth:api'], function () {
        Route::get('/{slug}', 'ChallengeController@show');
        Route::post('/create-session/{challenge_id}', 'ChallengeController@getChallengeSession');
    });

    Route::group(['namespace' => 'Api\V2', 'middleware' => 'auth:cyberq' ], function () {
        Route::any('/flag-submission', 'ChallengeController@flagSubmission');
        Route::any('/competition-completed', 'ChallengeController@competitionCompleted');
    });
});
