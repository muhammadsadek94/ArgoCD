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

// Prefix: /api/geography
Route::group(['prefix' => 'v1/geography'], function() {

   Route::group (['namespace' => 'Api\V1'], function(){
       Route::get('/allCountries', 'CountryController@index');

       Route::group(['auth:api,admin'], function (){
           Route::get("/get-cities", "GeographyControlerController@getCities");
           Route::get("/get-areas", "GeographyControlerController@getAreas");
           Route::get('/get-all-areas', "AreasController@index");
       });
   });


});

Route::group(['prefix' => 'v2/geography'], function() {

    Route::group (['namespace' => 'Api\V2'], function(){
        Route::get('/allCountries', 'CountryController@index');

        Route::group(['auth:api,admin'], function (){
            Route::get("/get-cities", "GeographyControlerController@getCities");
            Route::get("/get-areas", "GeographyControlerController@getAreas");
            Route::get('/get-all-areas', "AreasController@index");
        });
    });


 });
