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

// The controllers live in src/Domains/Course/Http/Controllers
use App\Domains\Uploads\Enum\UploadType;
use App\Domains\Uploads\Jobs\UploadFileJob;

Route::group(['namespace' => 'Admin', 'middleware' => 'admin.auth', 'prefix' => Constants::ADMIN_BASE_URL], function () {
    Route::get('/course-category/get_categories/actions', 'CourseCategoryController@get_categories');

    Route::resource('/course-category', 'CourseCategoryController');
    Route::post('/course-category/actions/upload', 'CourseCategoryController@postUploadPicture');
    Route::resource('/job-role', 'JobRoleController');
    Route::resource('/specialty-area', 'SpecialtyAreaController');
    Route::resource('/ksa', 'KsaController');
    Route::resource('/competency', 'CompetencyController');

    Route::get('/job-role/actions/get-job-roles', 'JobRoleController@searchJobRoles');
    Route::get('/specialty-area/actions/get-specialty-areas', 'SpecialtyAreaController@searchSpecialtyAreas');

    Route::resource('/course-tag', 'CourseTagController');
    Route::post('/course-tag/actions/upload', 'CourseTagController@postUploadPicture');
    Route::get('/course-tag/actions/get-tags', 'CourseTagController@searchTagsByCategory');

    Route::get('/course/duplicate', 'CourseController@duplicate');
    Route::resource('/course', 'CourseController');
    Route::post('/course/actions/upload-image', 'CourseController@postUploadPicture');
    Route::patch('/course/action/{course}/timing', 'CourseController@patchUpdateTiming');
    Route::get('/course/actions/get-trainers', 'CourseController@getTrainers');

    Route::post('/course/actions/package', 'CourseController@postPackage');
    Route::delete('/course/actions/package/{id}', 'CourseController@deletePackage');

    Route::resource('/proctor-user', 'ProctorUserController');


    Route::get('/micro-degree-course/duplicate', 'MicroDegreeCourseController@duplicate');
    Route::resource('/micro-degree-course', 'MicroDegreeCourseController');

    Route::get('/course-certification-course/duplicate', 'CourseCertificationCourseController@duplicate');
    Route::resource('/course-certification-course', 'CourseCertificationCourseController');

    Route::post('/micro-degree-course/actions/upload-image', 'MicroDegreeCourseController@postUploadPicture');
    Route::post('/micro-degree-course/actions/upload-pdf', 'MicroDegreeCourseController@postPdf');
    Route::get('/micro-degree-course/actions/get-trainers', 'MicroDegreeCourseController@getTrainers');

    Route::post('/micro-degree-course/actions/package', 'MicroDegreeCourseController@postPackage');
    Route::delete('/micro-degree-course/actions/package/{id}', 'MicroDegreeCourseController@deletePackage');

    Route::post('/micro-degree-course/actions/what-to-learn', 'MicroDegreeCourseController@postWhatToLearn');
    Route::delete('/micro-degree-course/actions/what-to-learn/{id}', 'MicroDegreeCourseController@deleteWhatToLearn');






    Route::post('/course-certification-course/actions/upload-image', 'MicroDegreeCourseController@postUploadPicture');
    Route::post('/course-certification-course/actions/upload-pdf', 'MicroDegreeCourseController@postPdf');
    Route::get('/course-certification-course/actions/get-trainers', 'MicroDegreeCourseController@getTrainers');

    Route::post('/course-certification-course/actions/package', 'MicroDegreeCourseController@postPackage');
    Route::delete('/course-certification-course/actions/package/{id}', 'MicroDegreeCourseController@deletePackage');

    Route::post('/course-certification-course/actions/what-to-learn', 'MicroDegreeCourseController@postWhatToLearn');
    Route::delete('/course-certification-course/actions/what-to-learn/{id}', 'MicroDegreeCourseController@deleteWhatToLearn');



    Route::resource('/chapter', 'ChapterController');
    Route::resource('/course-assessment', 'CourseAssessmentController');
    Route::resource('/lesson', 'LessonController');

    Route::post('/lesson/{id}/video/upload', 'LessonController@saveVideo');
    Route::post('/lesson/{id}/vimeo/upload', 'VimeoController@upload');
    Route::post('/lesson/{id}/vimeo/lesson-caption', 'VimeoController@getVideoCaption');
    Route::post('/lesson/{id}/vimeo/upload/caption', 'VimeoController@saveCaption');
    Route::post('/lesson/{id}/vimeo/update-caption', 'VimeoController@updateCaption');

    Route::resource('/lesson/actions/faq', 'LessonFaqController');
    Route::resource('/lesson/actions/resource', 'LessonResourceController');
    Route::resource('/lesson/actions/voucher', 'LessonVoucherController');
    Route::resource('/lesson/actions/quiz', 'LessonQuizController');
    Route::resource('/lesson/actions/lesson-objective', 'LessonObjectiveController');
    Route::resource('/lesson/actions/lesson-task', 'LessonTaskController');

    // application for lesson project
    Route::resource('/application-project', 'ProjectApplicationController');
    Route::post('/project-application/actions/{project}/complete', 'ProjectApplicationController@completeProject');
    Route::post('/project-application/actions/{project}/change-status', 'ProjectApplicationController@changeStatus');
    Route::post('/project-application/action/comment/{id}', 'ProjectApplicationController@addComment');

    //reviews
    Route::resource('/reviews', 'ReviewsController');
    Route::get('/reviews/change-status/{course}/{status}', 'ReviewsController@changeStatus');
});

Route::get('test/test/test','Admin\ProjectApplicationController@test');
