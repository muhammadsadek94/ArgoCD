<?php

/*
|--------------------------------------------------------------------------
| Service - Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for this service.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// The controllers live in src/Domains/Payments/Http/Controllers
Route::group(['namespace' => 'Admin', 'middleware'=>'admin.auth', 'prefix' => Constants::ADMIN_BASE_URL], function() {
    # Route::resource('/payments', 'PaymentsController');

    Route::get('/package-subscription/duplicate', 'PackageSubscriptionController@duplicate');
    Route::get('/payment-integration/duplicate', 'PaymentIntegrationController@duplicate');
    Route::resource('/package-subscription', 'PackageSubscriptionController');
    Route::resource('/payment-integration', 'PaymentIntegrationController');
    Route::resource('/learn-path', 'LearnPathController');
    Route::post('/learn-path/action/upload-image', 'LearnPathController@postUploadPicture');

    Route::post('/learn-path/actions/package', 'LearnPathController@postPackage');
    Route::delete('/learn-path/actions/package/{id}', 'LearnPathController@deletePackage');

     Route::resource('/subscription-cancellation', 'SubscriptionCancellationController');
     Route::get('/subscription-cancellation/actions/{id}/cancel', 'SubscriptionCancellationController@cancel');

     Route::post('/fetch-course-chapters', 'PackageSubscriptionController@fetchCourseChapters');
});




