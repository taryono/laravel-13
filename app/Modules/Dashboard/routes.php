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
    Route::any('/search', ['as' => 'dashboard.search', 'uses' => 'DashboardController@search']);
    Route::get('/create', ['as' => 'dashboard.create', 'uses' => 'DashboardController@create']);
    Route::get('/show/{id}', ['as' => 'dashboard.show', 'uses' => 'DashboardController@show']);
    Route::get('/preview/{id}', ['as' => 'dashboard.preview', 'uses' => 'DashboardController@preview']);
    Route::get('/print/{id}', ['as' => 'dashboard.print', 'uses' => 'DashboardController@print']);

    Route::post('/store', ['as' => 'dashboard.store', 'uses' => 'DashboardController@store']);
    Route::get('/edit/{id}', ['as' => 'dashboard.edit', 'uses' => 'DashboardController@edit']);
    Route::put('/update/{id}', ['as' => 'dashboard.update', 'uses' => 'DashboardController@update']);
    Route::delete('/delete/{id}', ['as' => 'dashboard.destroy', 'uses' => 'DashboardController@destroy']);
    
});
