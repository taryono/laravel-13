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
Route::group(['prefix' => 'system-log', 'namespace' => 'App\Modules\SystemLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'system_log.index', 'uses' => 'SystemLogController@index']);
    Route::any('/search', ['as' => 'system_log.search', 'uses' => 'SystemLogController@search']);
    Route::get('/create', ['as' => 'system_log.create', 'uses' => 'SystemLogController@create']);
    Route::get('/show/{id}', ['as' => 'system_log.show', 'uses' => 'SystemLogController@show']);
    Route::get('/preview/{id}', ['as' => 'system_log.preview', 'uses' => 'SystemLogController@preview']);
    Route::get('/print/{id}', ['as' => 'system_log.print', 'uses' => 'SystemLogController@print']);

    Route::post('/store', ['as' => 'system_log.store', 'uses' => 'SystemLogController@store']);
    Route::get('/edit/{id}', ['as' => 'system_log.edit', 'uses' => 'SystemLogController@edit']);
    Route::put('/update/{id}', ['as' => 'system_log.update', 'uses' => 'SystemLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'system_log.destroy', 'uses' => 'SystemLogController@destroy']);
    
});
