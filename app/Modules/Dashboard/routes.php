<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */
Route::group(['prefix' => 'dashboard', 'namespace' => 'App\Modules\Dashboard\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'dashboard.index', 'uses' => 'DashboardController@index']);
    Route::get('/back-to-home', ['as' => 'dashboard.back-to-home', 'uses' => 'DashboardController@backToHome']);
    Route::post('/select-category/{id?}', ['as' => 'dashboard.select-category', 'uses' => 'DashboardController@selectCategory']);
    Route::get('/select-category/{id?}', ['as' => 'dashboard.select-category.get', 'uses' => 'DashboardController@selectCategory']);
    Route::match(['get', 'post'], '/select-category/{id?}', 'DashboardController@selectCategory');
    Route::get('/cms', ['as' => 'dashboard.cms', 'uses' => 'DashboardController@cmsDirectView']);
    Route::get('/default-view', ['as' => 'dashboard.default-view', 'uses' => 'DashboardController@defaultView']);
});
