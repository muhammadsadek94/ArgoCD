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

Route::group(['prefix' => \App\Foundation\Enum\Constants::ADMIN_BASE_URL, 'middleware' => 'admin.auth', 'namespace' => 'Admin'], function() {

    // The controllers live in src/Domains/Configuration/Http/Controllers
     Route::resource('/configuration', 'ConfigurationController');
});
