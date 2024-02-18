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

// Prefix: /api/v1/user
Route::group(['prefix' => 'v1/user'], function () {

    Route::group(['namespace' => 'Api\V1\User'], function () {
        Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
            Route::group(['prefix' => 'regular'], function () {
                Route::post('register', 'AuthenticationController@register')->middleware(['ReCAPTCHA', 'throttle:20,1']);
                Route::post('create', 'AuthenticationController@registerWithOutPassword');
                Route::post('login', 'AuthenticationController@login');
            });

            Route::group(['prefix' => 'social-media'], function () {
                Route::post('register', 'SocialMediaAuthentication@register');
                Route::post('login', 'SocialMediaAuthentication@login');
                Route::post('linkedin', 'SocialMediaAuthentication@getLinkedIn');
                Route::post('github', 'SocialMediaAuthentication@getGithub');
                Route::post('twitter', 'SocialMediaAuthentication@getTwitter');
                Route::get('/login/{provider}', 'SocialMediaAuthentication@redirectToProvider');
                Route::get('/{provider}/callback', 'SocialMediaAuthentication@handleProviderCallback');

            });

            Route::group(['prefix' => 'forget-password', 'middleware' => ['ReCAPTCHA', 'throttle:20,1']], function () {
                Route::post('password/request', 'ForgetPasswordController@postSendResetPasswordCode');
                Route::post('password/valdiate', 'ForgetPasswordController@postValidateCode');
                Route::post('password/reset', 'ForgetPasswordController@postResetassword');
            });

            // logout - revoke user session
            Route::post('logout', 'AuthenticationController@logout');

            Route::group(['middleware' => ['auth:api', []]], function () {
                // Active user account
                Route::post('activate', 'ActivationController@postActiveAccount');
                Route::post('resend-activation-code', 'ActivationController@postResendActivationCode');


            });
        });

        Route::group(['prefix' => 'profile', 'namespace' => 'Profile', 'middleware' => 'auth:api'], function () {
            // get user profile details
            Route::get('/', 'ProfileController@getProfile');
//            Route::patch('/', 'ProfileController@patchProfile');
            Route::put('/', 'ProfileController@patchProfile');
            Route::post('/change-password', 'ProfileController@postChangePassword');
            Route::post('/check-password', 'ProfileController@postCheckPassword');

            Route::patch('/device-info', 'ProfileController@patchDeviceInfo');

            //            Route::post('/subscribe', 'ProfileController@subscribe');

            Route::post('/daily-target', 'ProfileController@postDailyTarget');


            Route::get('/pro-dashboard', 'ProfileController@getProUserDashboard');
            Route::get('/microdegree-dashboard', 'ProfileController@getMicroDegreeUserDashboard');

            Route::get('enrollments/microdegree', 'ProfileController@getEnrolledInMicrodegree');
            Route::get('enrollments/courses', 'ProfileController@getEnrolledInCourses');

            Route::get('categories/interests', 'ProfileController@getInterestsCategories');
            Route::get('courses/completed', 'ProfileController@getCompletedCourses');
           // Route::get('payments/invoices', 'ProfileController@getInvoices');
            //Route::post('payments/cancel-subscription', 'ProfileController@postCancelSubscriptionRequest');

            Route::get('learn-paths', 'ProfileController@getMightLikeLearnPaths');
            Route::get('purchased-learn-paths', 'ProfileController@getPurchasedLearnPaths');
            Route::get('courses/purchased-courses', 'ProfileController@getPurchasedCourses');
            Route::get('certificates', 'ProfileController@getCertificates');

            Route::get('courses/lab-courses', 'ProfileController@getCoursesWithLabs');

            Route::get('/onboard-quiz', 'OnBoardingQuizController@index');
            Route::post('/onboard-quiz', 'OnBoardingQuizController@store');


            Route::group(['prefix' => 'email'], function () {
                Route::post('request', 'UpdateEmailController@postRequest');
                Route::post('verify', 'UpdateEmailController@postVerify');
            });

            Route::group(['prefix' => 'phone'], function () {
                Route::post('request', 'UpdatePhoneController@postRequest');
                Route::post('verify', 'UpdatePhoneController@postVerify');
            });

        });

    });
});


Route::group(['prefix' => 'v1/instructor'], function () {

    Route::group(['namespace' => 'Api\V1\Instructor'], function () {

        Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
            Route::group(['prefix' => 'regular'], function () {
                Route::post('register', 'RegisterController@register')->middleware('throttle:20,1');
                Route::post('register/validate', 'RegisterController@validateRegistration')->middleware('throttle:20,1');
                Route::post('login', 'AuthenticationController@login')->middleware('throttle:20,1');
            });


            Route::group(['prefix' => 'forget-password'], function () {
                Route::post('password/request', 'ForgetPasswordController@postSendResetPasswordCode')->middleware('throttle:20,1');
                Route::post('password/valdiate', 'ForgetPasswordController@postValidateCode')->middleware('throttle:20,1');
                Route::post('password/reset', 'ForgetPasswordController@postResetassword')->middleware('throttle:20,1');
            });

            Route::post('logout', 'AuthenticationController@logout');
        });

        Route::group(['prefix' => 'profile', 'namespace' => 'Profile', 'middleware' => 'auth:api'], function () {
            Route::get('/', 'ProfileController@getProfile');
            Route::patch('/bank-info', 'ProfileController@patchBankInfo');
            Route::patch('/', 'ProfileController@patchProfile');
            Route::post('/change-password', 'ProfileController@postChangePassword');
        });

        Route::group(['prefix' => 'app', 'middleware' => 'auth:api'], function () {
            Route::get('/dashboard', 'DashboardController@index');
            Route::get('/payouts', 'DashboardController@payouts');
        });
        Route::get('/{id}', 'InstructorController@getInstructor');
    });
});



Route::any('v1/user/profile/order-completed/strip', '\App\Domains\Payments\Http\Controllers\Api\V1\User\PaymentsController@postStripCharge');
Route::any('v1/user/profile/order-cancelled', '\App\Domains\Payments\Http\Controllers\Api\V1\User\PaymentsController@orderCancelled');
Route::any('v1/user/profile/order-completed', '\App\Domains\Payments\Http\Controllers\Api\V1\User\PaymentsController@orderCompleted');

//Route::any('v1/user/profile/order-completed', 'Api\V1\User\PaymentsController@orderCompleted');

//Route::any('v1/user/profile/order-completed/strip', 'Api\V1\User\PaymentsController@postStripCharge');


//Route::any('v1/user/profile/order-cancelled', 'Api\V1\User\PaymentsController@orderCancelled');
// Route::any('v1/user/profile/order-completed', 'Api\V1\User\PaymentsController@orderCompleted');
// Route::any('v1/user/profile/order-completed/strip', 'Api\V1\User\PaymentsController@postStripCharge');

// Route::any('v1/user/profile/order-cancelled', 'Api\V1\User\PaymentsController@orderCancelled');


////////////////////////
//////// version URL v2
////////////////////////
Route::group(['prefix' => 'v2/user'], function() {
    Route::group(['namespace' => 'Api\V2\User'], function() {
        Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function() {
            //, z
            Route::group(['prefix' => 'regular'], function() {


                Route::group(['middleware' => ['ReCAPTCHA', 'throttle:20,1']], function() {
                    Route::post('register', 'AuthenticationController@register');
                    Route::post('login', 'AuthenticationController@login');
                });

                Route::post('create', 'AuthenticationController@registerWithOutPassword');


            });

            Route::group(['prefix' => 'social-media'], function() {
                Route::post('register', 'SocialMediaAuthentication@register');
                Route::post('login', 'SocialMediaAuthentication@login');
                Route::post('linkedin', 'SocialMediaAuthentication@getLinkedIn');
                Route::post('github', 'SocialMediaAuthentication@getGithub');
                Route::post('twitter', 'SocialMediaAuthentication@getTwitter');
                Route::get('/login/{provider}', 'SocialMediaAuthentication@redirectToProvider');
                Route::get('/{provider}/callback', 'SocialMediaAuthentication@handleProviderCallback');
            });

            Route::group(['prefix' => 'forget-password', 'middleware' => ['ReCAPTCHA', 'throttle:20,1']], function() {
                Route::post('password/request', 'ForgetPasswordController@postSendResetPasswordCode');
                Route::post('password/valdiate', 'ForgetPasswordController@postValidateCode');
                Route::post('password/reset', 'ForgetPasswordController@postResetassword');
            });

            Route::group(['middleware' => ['auth:api', 'throttle:20,1']], function() {
                // Active user account
                Route::post('activate', 'ActivationController@postActiveAccount');
                Route::post('resend-activation-code', 'ActivationController@postResendActivationCode');

                // logout - revoke user session
                Route::post('logout', 'AuthenticationController@logout');
            });
        });

        Route::group(['prefix' => 'profile', 'namespace' => 'Profile', 'middleware' => 'auth:api'], function() {
            Route::get('/', 'ProfileController@getProfile');
            Route::patch('/', 'ProfileController@patchProfile');
            Route::post('/change-password', 'ProfileController@postChangePassword');
            Route::patch('/device-info', 'ProfileController@patchDeviceInfo');

            //            Route::post('/subscribe', 'ProfileController@subscribe');

            Route::post('/daily-target', 'ProfileController@postDailyTarget');
            Route::post('/weekly-target', 'ProfileController@postWeeklyTarget');
            Route::post('/selected-days', 'ProfileController@postSelectedDays');


            Route::get('/pro-dashboard', 'ProfileController@getProUserDashboard');
            Route::get('/pro-home', 'ProfileController@getProUserHome');
            Route::get('/my-courses', 'ProfileController@getMyCourses');
            Route::get('/home', 'ProfileController@getNonProUserHome');
            Route::get('/statistics', 'ProfileController@getProUserStatistics');
            Route::get('/microdegree-dashboard', 'ProfileController@getMicroDegreeUserDashboard');

            Route::get('enrollments/microdegree', 'ProfileController@getEnrolledInMicrodegree');
            Route::get('enrollments/courses', 'ProfileController@getEnrolledInCourses');

            Route::get('categories/interests', 'ProfileController@getInterestsCategories');
            Route::get('courses/completed', 'ProfileController@getCompletedCourses');
            Route::get('payments/invoices', 'ProfileController@getInvoices');
            Route::post('payments/cancel-subscription', 'ProfileController@postCancelSubscriptionRequest');


            Route::get('/onboard-quiz', 'OnBoardingQuizController@index');
            Route::post('/onboard-quiz', 'OnBoardingQuizController@store');


            Route::group(['prefix' => 'email'], function() {
                Route::post('request', 'UpdateEmailController@postRequest');
                Route::post('verify', 'UpdateEmailController@postVerify');
            });

            Route::group(['prefix' => 'phone'], function() {
                Route::post('request', 'UpdatePhoneController@postRequest');
                Route::post('verify', 'UpdatePhoneController@postVerify');
            });

            Route::get('/user_pro', 'ProfileController@userPro');

            Route::post('/is_updated_display_name', 'ProfileV2Controller@checkDisplayName');
            Route::post('/update_display_name', 'ProfileV2Controller@updateDisplayName');

        });

    });
});


Route::group(['prefix' => 'v2/instructor'], function() {

    Route::group(['namespace' => 'Api\V2\Instructor'], function() {

        Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function() {
            Route::group(['prefix' => 'regular'], function() {
                Route::post('register', 'RegisterController@register');
                Route::post('register/validate', 'RegisterController@validateRegistration');
                Route::post('login', 'AuthenticationController@login')->middleware('throttle:20,1');
            });


            Route::group(['prefix' => 'forget-password'], function() {
                Route::post('password/request', 'ForgetPasswordController@postSendResetPasswordCode');
                Route::post('password/valdiate', 'ForgetPasswordController@postValidateCode');
                Route::post('password/reset', 'ForgetPasswordController@postResetassword');
            });



            Route::post('logout', 'AuthenticationController@logout');
        });

        Route::group(['prefix' => 'profile', 'namespace' => 'Profile', 'middleware' => 'auth:api'], function() {
            // get user profile details
            Route::get('/', 'ProfileController@getProfile');
            Route::patch('/', 'ProfileController@patchProfile');
            Route::post('/change-password', 'ProfileController@postChangePassword');
        });

        Route::group(['prefix' => 'app','middleware' => 'auth:api'], function() {
            Route::get('/dashboard', 'DashboardController@index');
            Route::get('/payouts', 'DashboardController@payouts');
        });
        Route::get('/{id}', 'InstructorController@getInstructor');
    });
});
