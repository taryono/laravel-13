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
Route::group(['prefix' => 'cbt-activity-log', 'namespace' => 'App\Modules\CbtActivityLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cbt_activity_log.index', 'uses' => 'CbtActivityLogController@index']);
    Route::any('/search', ['as' => 'cbt_activity_log.search', 'uses' => 'CbtActivityLogController@search']);
    Route::get('/create', ['as' => 'cbt_activity_log.create', 'uses' => 'CbtActivityLogController@create']);
    Route::get('/show/{id}', ['as' => 'cbt_activity_log.show', 'uses' => 'CbtActivityLogController@show']);
    Route::get('/preview/{id}', ['as' => 'cbt_activity_log.preview', 'uses' => 'CbtActivityLogController@preview']);
    Route::get('/print/{id}', ['as' => 'cbt_activity_log.print', 'uses' => 'CbtActivityLogController@print']);

    Route::post('/store', ['as' => 'cbt_activity_log.store', 'uses' => 'CbtActivityLogController@store']);
    Route::get('/edit/{id}', ['as' => 'cbt_activity_log.edit', 'uses' => 'CbtActivityLogController@edit']);
    Route::put('/update/{id}', ['as' => 'cbt_activity_log.update', 'uses' => 'CbtActivityLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'cbt_activity_log.destroy', 'uses' => 'CbtActivityLogController@destroy']);
    
});
