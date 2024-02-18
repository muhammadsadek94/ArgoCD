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

// The controllers live in src/Domains/Seo/Http/Controllers
Route::group(['namespace' => 'Admin', 'middleware'=>'admin.auth', 'prefix' => Constants::ADMIN_BASE_URL], function() {
    Route::get('sitemap/generate-site-map', 'SitemapController@index');
});

