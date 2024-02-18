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

// Prefix: /api/v1/course
Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function () {
    Route::group(['namespace' => 'User'], function () {
        Route::group(['prefix' => 'course'], function () {
            //api/v1/course/featured-courses
            //new home page
            Route::get('/actions/featured', 'CourseController@getFeaturedCourses');
            //new pro-page
            Route::get('/actions/propage', 'CourseController@getProPageDetails');
            Route::get('/actions/lookups', 'CourseController@getLookups');

            Route::get('actions/library', 'CourseController@getLibrary');
            Route::group(['middleware' => 'auth:api'], function () {
                Route::get('/{course_id}/internal', 'CourseController@getInternal');
                Route::post('/{course_id}/actions/enroll', 'CourseController@postEnroll');
            });
            Route::any('actions/filtration', 'CourseController@getCourseFiltration');
            Route::get('/{course_id}', 'CourseController@show');
        });
        Route::get('/lab-courses', 'CourseController@getCoursesWithLabs');

        Route::group(['prefix' => 'microdegree'], function () {
            Route::get('/actions/courses', 'MicrodegreeController@getMicrodegrees');
            Route::get('/actions/{course_id}/show', 'MicrodegreeController@show');

            Route::group(['middleware' => 'auth:api'], function () {
                Route::get('/{course_id}/internal', 'MicrodegreeController@getInternal');
                Route::post('/{course_id}/actions/enroll', 'MicrodegreeController@postEnroll');
            });
        });

        Route::group(['prefix' => 'lesson', 'middleware' => 'auth:api'], function () {
            Route::get('/{lesson_id}', 'LessonController@show');
            Route::post('/{lesson_id}/action/note', 'LessonNotesController@store');
            Route::patch("/note/{note_id}", "LessonNotesController@update");
            Route::delete('/deletenote/{note_id}', 'LessonNotesController@destroy');
            Route::get('/action/note', 'LessonController@getUserNotes');
            Route::post('/{lesson_id}/action/watched', 'LessonController@postWatched');
            Route::post('/{lesson_id}/action/watchedTime', 'LessonController@postWatchedTime');
            Route::post('/{lesson_id}/action/task-interaction', 'LessonController@postTaskInteraction');
            Route::post('/{lesson_id}/action/ilab', 'LessonController@getIlabSession');
            Route::post('/{lesson_id}/action/assign-voucher', 'LessonController@assignVoucher');
        });

        Route::group(['prefix' => 'assessment', 'middleware' => 'auth:api'], function () {
            Route::post('/{course_id}/validate', 'AssessmentController@validateCredentials');
            Route::get('/{course_id}', 'AssessmentController@show');
            Route::post('/{course_id}', 'AssessmentController@store');
            Route::post('/{course_id}/update', 'AssessmentController@storeAnswer');
            Route::post("/{course_id}/course_reviews", "CourseReviewController@store");
        });
    });

    Route::get('results/{id}/result', 'User\AssessmentController@getResult');
});


// Prefix: /api/v2/homepage
Route::group(['prefix' => 'v2', 'namespace' => 'Api\V2'], function () {
    Route::get('homepage', 'HomepageController@index');
    Route::get('menu', 'HomepageController@menu');

    Route::post('course/actions/get-syllabus', 'SyllabusController@postGetSyllabus');

    Route::group(['namespace' => 'User'], function () {
        Route::post('course/update-bulk-advances', 'CourseController@updateBulkAdvances');//to be changed

        Route::group(['prefix' => 'lesson', 'middleware' => 'auth:api'], function () {
            Route::get('/action/note', 'LessonController@getUserNotes');
        });

        Route::group(['prefix' => 'course'], function () {
            Route::any('actions/filtration', 'CourseController@getCourseFiltration');
            Route::get('/actions/propage', 'CourseController@getProPageDetails');
            Route::any('actions/autocomplete', 'CourseController@autocomplete');
            Route::get('/{course_id}', 'CourseController@show');
            Route::get('/{course_id}/internal', 'CourseController@getInternal');
            Route::post('/{course_id}/course_survey', 'CourseController@postSurvey');

            Route::group(['middleware' => 'auth:api'], function () {
                Route::post('/{course_id}/actions/enroll', 'CourseController@postEnroll');
            });
        });
    });

    Route::group(['prefix' => 'microdegree', 'namespace' => 'User'], function () {
        Route::get('/show/{id}', 'MicrodegreeController@show');
        Route::get('/show/internal/{course_id}', 'MicrodegreeController@internal');
        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('/{course_id}/internal', 'MicrodegreeController@getInternal');
        });
    });

    Route::get('/lab-courses', 'CourseController@getCoursesWithLabs');

    Route::group(['prefix' => 'microdegree', 'namespace' => 'User'], function () {
        Route::get('/actions/courses', 'MicrodegreeController@getMicrodegrees');
        Route::get('/actions/{course_id}/show', 'MicrodegreeController@show');

        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('/{course_id}/internal', 'MicrodegreeController@getInternal');
            Route::post('/{course_id}/actions/enroll', 'MicrodegreeController@postEnroll');
        });
    });

    Route::group(['prefix' => 'free-lesson', 'namespace' => 'User',], function () {
        Route::get('/{lesson_id}', 'LessonController@free');
    });

    Route::group(['prefix' => 'lesson', 'namespace' => 'User', 'middleware' => 'auth:api'], function () {
        Route::get('/{lesson_id}', 'LessonController@show');
        Route::post('/{lesson_id}/action/note', 'LessonNotesController@store');
        Route::patch("/note/{note_id}", "LessonNotesController@update");
        Route::delete('/deletenote/{note_id}', 'LessonNotesController@destroy');
        Route::get('/action/note', 'LessonController@getUserNotes');
        Route::post('/{lesson_id}/action/watched', 'LessonController@postWatched');
        Route::post('/{lesson_id}/action/watchedTime', 'LessonController@postWatchedTime');
        Route::post('/{lesson_id}/action/task-interaction', 'LessonController@postTaskInteraction');
        Route::post('/{lesson_id}/action/ilab', 'LessonController@getIlabSession');
        Route::post('/{lesson_id}/action/vital-source', 'LessonController@createVitalSourceSession');

        //Cyberq Test
        Route::post('/{lesson_id}/action/cyberq', 'CyberQController@getCyberqDetails');

        Route::post('/{lesson_id}/action/assign-voucher', 'LessonController@assignVoucher');
        Route::post('/{lesson_id}/action/add-project-application', 'LessonController@addProjectApplication');
        Route::post('/{project_application_id}/action/add-comment', 'LessonController@addCommentProjectApplication');
    });

    Route::group(['prefix' => 'assessment', 'namespace' => 'User', 'middleware' => 'auth:api'], function () {

        Route::post('/{course_id}/validate', 'AssessmentController@validateCredentials');

        Route::get('/{course_id}', 'AssessmentController@show');
        Route::post('/{course_id}', 'AssessmentController@store');
        Route::post('/{course_id}/update', 'AssessmentController@storeAnswer');
        Route::post("/{course_id}/course_reviews", "CourseReviewController@store");
    });

    Route::get('results/{id}/result', 'User\AssessmentController@getResult');
});

Route::any('v2/cyberq-validation', 'Api\V2\User\LessonController@validateCyberQSession')->middleware('auth:cyberq');
