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
Route::group(['prefix' => 'access-log', 'namespace' => 'App\Modules\AccessLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'access-log.index', 'uses' => 'AccessLogController@index']);
    Route::any('/search', ['as' => 'access-log.search', 'uses' => 'AccessLogController@search']);
    Route::get('/create', ['as' => 'access-log.create', 'uses' => 'AccessLogController@create']);
    Route::get('/show/{access-log}', ['as' => 'access-log.show', 'uses' => 'AccessLogController@show']);
    Route::get('/preview/{access-log}', ['as' => 'access-log.preview', 'uses' => 'AccessLogController@preview']);
    Route::get('/print/{access-log}', ['as' => 'access-log.print', 'uses' => 'AccessLogController@print']);

    Route::post('/store', ['as' => 'access-log.store', 'uses' => 'AccessLogController@store']);
    Route::get('/edit/{access-log}', ['as' => 'access-log.edit', 'uses' => 'AccessLogController@edit']);
    Route::put('/update/{access-log}', ['as' => 'access-log.update', 'uses' => 'AccessLogController@update']);
    Route::delete('/delete/{access-log}', ['as' => 'access-log.destroy', 'uses' => 'AccessLogController@destroy']);
    
});
