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

// Prefix: /api/v1/open_api
Route::group(['prefix' => 'v1/open-api', 'namespace' => 'Api\V1'], function () {

    //prefix: /user/..
    Route::group(['namespace' => 'User'], function() {
    /*Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {*/
        Route::group(['prefix' => 'user'], function() {

                Route::post('/create', 'UserController@register');

                Route::group(['middleware' => 'auth:api'], function () {

                    Route::get('/', 'UserController@show');
                    Route::patch('/', 'UserController@update');
                    Route::post('/subscribe', 'SubscriptionController@subscribe')
                        ->middleware('scopes:'. \App\Domains\OpenApi\Enum\AccessScope::UPDATE_SUBSCRIPTIONS);

                    Route::post('/subscribe/revoke', 'SubscriptionController@revoke')
                        ->middleware('scopes:'. \App\Domains\OpenApi\Enum\AccessScope::REVOKE_SUBSCRIPTIONS);

                    Route::get('/actions/enrolled-courses', 'UserController@getEnrolledCourses');

                    Route::get('/actions/my-courses', 'UserController@getAvailableCourses');

                    Route::get('/actions/user-certificate', 'UserController@getUserCertificates');


                });


        });

        Route::group(['prefix' => 'course'], function() {

                Route::get('/actions/featured-courses', 'CourseController@getFeaturedCourses');

        });

    });
});


//////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////V2/V2/V2/V2/V2/V2/V2/V2//V2/V2/V2/V2//////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

Route::group(['prefix' => 'v2/open-api', 'namespace' => 'Api\V2'], function () {

    //prefix: /user/..
    Route::group(['namespace' => 'User'], function() {
    /*Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {*/
        Route::group(['prefix' => 'user'], function() {

                Route::group(['middleware' => 'auth:api'], function () {
                    Route::get('/actions/user-certificate', 'UserController@getUserCertificates');
                    Route::get('/actions/my-learning-paths', 'UserController@myLearningPaths');
                    Route::get('/actions/my-bundles', 'UserController@myBundles');
                    Route::get('/actions/my-microdegrees', 'UserController@myMicroDegrees');
                    Route::get('/actions/my-courses', 'UserController@myCourses');
                    Route::get('/actions/my-certifications', 'UserController@myCertifications');
                });
                Route::get('/actions/certificate/{id}', 'UserController@getUserCertificateById');
                
        });
    });
});
