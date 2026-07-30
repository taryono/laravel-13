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
Route::group(['prefix' => 'schedule', 'namespace' => 'App\Modules\Schedule\Controllers', 'middleware' => ['web', 'auth']], function () {
    Route::get('/', ['as' => 'schedule.index', 'uses' => 'ScheduleController@index']);
    Route::any('/search', ['as' => 'schedule.search', 'uses' => 'ScheduleController@search']);
    Route::get('/create', ['as' => 'schedule.create', 'uses' => 'ScheduleController@create']);
    Route::get('/show/{schedule}', ['as' => 'schedule.show', 'uses' => 'ScheduleController@show']);
    Route::get('/preview/{schedule}', ['as' => 'schedule.preview', 'uses' => 'ScheduleController@preview']);
    Route::get('/print/{schedule}', ['as' => 'schedule.print', 'uses' => 'ScheduleController@print']);

    Route::post('/store', ['as' => 'schedule.store', 'uses' => 'ScheduleController@store']);
    Route::get('/edit/{schedule}', ['as' => 'schedule.edit', 'uses' => 'ScheduleController@edit']);
    Route::put('/update/{schedule}', ['as' => 'schedule.update', 'uses' => 'ScheduleController@update']);
    Route::delete('/delete/{schedule}', ['as' => 'schedule.destroy', 'uses' => 'ScheduleController@destroy']);
    
});
