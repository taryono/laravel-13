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
Route::group(['prefix' => 'dapodik-sync-log', 'namespace' => 'App\Modules\DapodikSyncLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'dapodik_sync_log.index', 'uses' => 'DapodikSyncLogController@index']);
    Route::any('/search', ['as' => 'dapodik_sync_log.search', 'uses' => 'DapodikSyncLogController@search']);
    Route::get('/create', ['as' => 'dapodik_sync_log.create', 'uses' => 'DapodikSyncLogController@create']);
    Route::get('/show/{id}', ['as' => 'dapodik_sync_log.show', 'uses' => 'DapodikSyncLogController@show']);
    Route::get('/preview/{id}', ['as' => 'dapodik_sync_log.preview', 'uses' => 'DapodikSyncLogController@preview']);
    Route::get('/print/{id}', ['as' => 'dapodik_sync_log.print', 'uses' => 'DapodikSyncLogController@print']);

    Route::post('/store', ['as' => 'dapodik_sync_log.store', 'uses' => 'DapodikSyncLogController@store']);
    Route::get('/edit/{id}', ['as' => 'dapodik_sync_log.edit', 'uses' => 'DapodikSyncLogController@edit']);
    Route::put('/update/{id}', ['as' => 'dapodik_sync_log.update', 'uses' => 'DapodikSyncLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'dapodik_sync_log.destroy', 'uses' => 'DapodikSyncLogController@destroy']);
    
});
