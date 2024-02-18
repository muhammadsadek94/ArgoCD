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

// Prefix: /api/v1/payments
    Route::group(['prefix' => 'v1/payments'], function() {

        // The controllers live in src/Domains/Payments/Http/Controllers
        // Route::resource('/payments', 'PaymentsController');
    });


    Route::group(['prefix' => 'v1/configuration'], function() {

        Route::get('/packages', 'Api\V1\User\PackageSubscriptionController@getPackages')->middleware(['auth:api']);

    });


    Route::group(['prefix' => 'v1/user'], function() {
        Route::group(['namespace' => 'Api\V1\User'], function() {

            Route::any('profile/order-completed', 'PaymentsController@orderCompleted');

            Route::group(['prefix' => 'profile',  'middleware' => 'auth:api'], function() {

                     Route::get('payments/invoices', 'PaymentsController@getInvoices');
                     Route::post('payments/cancel-subscription', 'PaymentsController@postCancelSubscriptionRequest');



            });
        });
    });
Route::group([ 'middleware' => 'auth:api'], function() {

    Route::any('v1/user/profile/learnPath/{id}', '\App\Domains\User\Http\Controllers\Api\V1\User\PaymentsController@LearnPathById');
});


////////////////V2///////////////////
    Route::group(['prefix' => 'v2/learnpath'], function() {
        Route::group(['namespace' => 'Api\V2\LearnPath'], function() {
            Route::get('/filtration', 'LearnPathController@filterLearnPath');

            Route::get('{id}', 'LearnPathController@getLearningPathById');
            Route::post('{id}/actions/assign', 'LearnPathController@assignLearenPath');
        });
    });


    Route::group(['prefix' => 'v2/configuration'], function() {

        Route::get('/packages', 'Api\V2\User\PackageSubscriptionController@getPackages')->middleware(['auth:api']);

    });


//Route::any('v1/user/profile/order-completed', 'Api\V1\User\PaymentsController@orderCompleted');
