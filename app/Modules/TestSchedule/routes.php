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
Route::group(['prefix' => 'test-schedule', 'namespace' => 'App\Modules\TestSchedule\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'test_schedule.index', 'uses' => 'TestScheduleController@index']);
    Route::any('/search', ['as' => 'test_schedule.search', 'uses' => 'TestScheduleController@search']);
    Route::get('/create', ['as' => 'test_schedule.create', 'uses' => 'TestScheduleController@create']);
    Route::get('/show/{id}', ['as' => 'test_schedule.show', 'uses' => 'TestScheduleController@show']);
    Route::get('/preview/{id}', ['as' => 'test_schedule.preview', 'uses' => 'TestScheduleController@preview']);
    Route::get('/print/{id}', ['as' => 'test_schedule.print', 'uses' => 'TestScheduleController@print']);

    Route::post('/store', ['as' => 'test_schedule.store', 'uses' => 'TestScheduleController@store']);
    Route::get('/edit/{id}', ['as' => 'test_schedule.edit', 'uses' => 'TestScheduleController@edit']);
    Route::put('/update/{id}', ['as' => 'test_schedule.update', 'uses' => 'TestScheduleController@update']);
    Route::delete('/delete/{id}', ['as' => 'test_schedule.destroy', 'uses' => 'TestScheduleController@destroy']);
    
});
