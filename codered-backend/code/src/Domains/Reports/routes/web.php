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

// The controllers live in src/Domains/Reports/Http/Controllers
Route::group(['namespace' => 'Admin', 'middleware'=>'admin.auth', 'prefix' => Constants::ADMIN_BASE_URL], function() {
   
     Route::get('/summary-report', 'ReportController@getReports')->name('getReports');

     Route::resource('/lesson-report', 'LessonReportController');
     Route::resource('/global-knowledge-report', 'GlobalKnowledgeReportController');
     Route::get('global-knowledge-report/action/export/', 'GlobalKnowledgeReportController@export')->name('export');

});

