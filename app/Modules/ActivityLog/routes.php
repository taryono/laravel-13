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
Route::group(['prefix' => 'activity-log', 'namespace' => 'App\Modules\ActivityLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'activity_log.index', 'uses' => 'ActivityLogController@index']);
    Route::any('/search', ['as' => 'activity_log.search', 'uses' => 'ActivityLogController@search']);
    Route::get('/create', ['as' => 'activity_log.create', 'uses' => 'ActivityLogController@create']);
    Route::get('/show/{id}', ['as' => 'activity_log.show', 'uses' => 'ActivityLogController@show']);
    Route::get('/preview/{id}', ['as' => 'activity_log.preview', 'uses' => 'ActivityLogController@preview']);
    Route::get('/print/{id}', ['as' => 'activity_log.print', 'uses' => 'ActivityLogController@print']);

    Route::post('/store', ['as' => 'activity_log.store', 'uses' => 'ActivityLogController@store']);
    Route::get('/edit/{id}', ['as' => 'activity_log.edit', 'uses' => 'ActivityLogController@edit']);
    Route::put('/update/{id}', ['as' => 'activity_log.update', 'uses' => 'ActivityLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'activity_log.destroy', 'uses' => 'ActivityLogController@destroy']);
    
});
