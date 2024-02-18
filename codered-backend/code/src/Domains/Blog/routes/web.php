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

// The controllers live in src/Domains/Blog/Http/Controllers
Route::group(['namespace' => 'Admin', 'middleware'=>'admin.auth', 'prefix' => Constants::ADMIN_BASE_URL], function() {
    # Route::resource('/blog', 'BlogController');
    Route::resource('/article-category', 'ArticleCategoryController');
    Route::resource('/article', 'ArticleController');
    Route::post('/blog/actions/upload-image', 'ArticleController@postUploadPicture');
    Route::resource('/quote', 'QuoteController');
    Route::post('/quote/action/upload-author-image', 'QuoteController@postUploadPicture');

});

