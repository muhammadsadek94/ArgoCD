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

// Prefix: /api/v1/enterprise
Route::group(['prefix' => 'v1/enterprise'], function () {

    // The controllers live in src/Domains/Enterprise/Http/Controllers
    // Route::resource('/enterprise', 'EnterpriseController');


    Route::group(['middleware' => 'auth:api'], function () {

        Route::group(['namespace' => 'Api\V1\User'], function () {
            Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
                Route::group(['prefix' => 'regular'], function () {
                    Route::post('register', 'AuthenticationController@register');
                });
            });
            Route::post('users/learn-paths', 'GetUserController@addNewLearnPath');
            Route::get('users-tags', 'GetUserController@getTags');
            Route::delete('users/learn-paths/{id}', 'GetUserController@deleteUserLearnPath');
            Route::post('users/deactivate-list', 'GetUserController@deleteAll');
            Route::resource('users', 'GetUserController');
            Route::get('/info', 'EnterpriseController@getEnterpriseData');
            Route::post('/user-file', 'EnterpriseController@createUserWithFile');
            Route::patch('/profile', 'EnterpriseController@patchProfile');
            Route::post('/profile/change-password', 'EnterpriseController@postChangePassword');

        });
    });

//reports
    Route::group(['middleware' => 'auth:api'], function () {

        Route::group(['namespace' => 'Api\V1\Reports'], function () {
            Route::group(['prefix' => 'reports'], function () {

                Route::get('users', 'UserReportsController@users');
                Route::get('users-completion-rate', 'UserReportsController@usersCompletionRate');
                Route::get('users-completed_course', 'UserReportsController@usersCompletedCourse');
                Route::get('users-enrollment', 'UserReportsController@usersEnrollment');
                Route::get('users-score', 'UserReportsController@usersScore');


                Route::get('subAccounts', 'SubAccountReportsController@subAccounts');
                Route::get('subAccounts-completion-rate', 'SubAccountReportsController@subAccountsCompletionRate');
                Route::get('subAccounts-completed_course', 'SubAccountReportsController@subAccountsCompletedCourse');
                Route::get('subAccounts-enrollment', 'SubAccountReportsController@subAccountsEnrollment');
                Route::get('subAccounts-score', 'SubAccountReportsController@subAccountsScore');


                Route::get('courses', 'CoursesReportsController@courses');
                Route::get('courses-completion-rate', 'CoursesReportsController@coursesCompletionRate');
                Route::get('courses-rating', 'CoursesReportsController@coursesRating');
                Route::get('courses-enrollment', 'CoursesReportsController@coursesEnrollment');
                Route::get('courses-score', 'CoursesReportsController@coursesScore');

                Route::get('dashboard', 'DashBoardReportsController@dashBoard');

            });
        });
    });

// learnpaths
    Route::group(['namespace' => 'Api\V1\LearnPath'], function () {
        Route::group(['middleware' => 'auth:api'], function () {

            Route::apiResource('/learn-paths', 'LearnPathController');
            Route::post('/learn-paths/action/assign-courses/{package_subscription_id}', 'LearnPathController@assignCourse');
            Route::post('/learn-paths/action/assign-users/{package_subscription_id}', 'LearnPathController@assignUser');
            Route::post('/learn-paths/action/remove-assign-users/{id}', 'LearnPathController@removeAssignUser');
            Route::get('/learn-paths/action/filter-courses', 'LearnPathController@filterCourse');
            Route::get('/learn-paths/action/filtration', 'LearnPathController@filtration');
            Route::get('/learn-paths/action/filter-tags', 'LearnPathController@filterTags');

        });
    });
    Route::group(['namespace' => 'Api\V1\Tableau'], function () {
//        Route::group(['middleware' => 'auth:api'], function () {

        Route::apiResource('/tableau', 'TableauController');

//        });
    });

    Route::group(['namespace' => 'Api\V1\User'], function () {

        Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
            Route::group(['prefix' => 'regular'], function () {
                Route::post('login', 'AuthenticationController@login')->middleware('throttle:5,10');
            });

            Route::group(['prefix' => 'forget-password'], function () {
                Route::post('password/request', 'ForgetPasswordController@postSendResetPasswordCode')->middleware('ReCAPTCHA','throttle:5,10');
                Route::post('password/valdiate', 'ForgetPasswordController@postValidateCode')->middleware('throttle:5,10');
                Route::post('password/reset', 'ForgetPasswordController@postResetassword')->middleware('throttle:5,10');
            });

            Route::group(['middleware' => 'auth:api'], function () {
                // Active user account
                Route::post('activate', 'ActivationController@postActiveAccount');
                Route::post('resend-activation-code', 'ActivationController@postResendActivationCode');

                // logout - revoke user session
                Route::post('logout', 'AuthenticationController@logout');

            });
        });
    });


    Route::group(['middleware' => 'auth:api'], function () {
        Route::group(['namespace' => 'Api\V1\SubAccount'], function () {
            Route::resource('subAccount', 'SubAccountController');

            Route::post('subAccount/learn-paths', 'SubAccountController@addNewSubAccountLearnPath');
            Route::post('subAccount/license', 'SubAccountController@addNewSubAccountLicense');
            Route::delete('subAccount/learn-paths/{id}', 'SubAccountController@deleteSubAccountLearnPath');

        });

    });
});
