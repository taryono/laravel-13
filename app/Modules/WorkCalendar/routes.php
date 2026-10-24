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
Route::group(['prefix' => 'work-calendar', 'namespace' => 'App\Modules\WorkCalendar\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'work_calendar.index', 'uses' => 'WorkCalendarController@index']);
    Route::any('/search', ['as' => 'work_calendar.search', 'uses' => 'WorkCalendarController@search']);
    Route::get('/create', ['as' => 'work_calendar.create', 'uses' => 'WorkCalendarController@create']);
    Route::get('/show/{id}', ['as' => 'work_calendar.show', 'uses' => 'WorkCalendarController@show']);
    Route::get('/preview/{id}', ['as' => 'work_calendar.preview', 'uses' => 'WorkCalendarController@preview']);
    Route::get('/print/{id}', ['as' => 'work_calendar.print', 'uses' => 'WorkCalendarController@print']);

    Route::post('/store', ['as' => 'work_calendar.store', 'uses' => 'WorkCalendarController@store']);
    Route::get('/edit/{id}', ['as' => 'work_calendar.edit', 'uses' => 'WorkCalendarController@edit']);
    Route::put('/update/{id}', ['as' => 'work_calendar.update', 'uses' => 'WorkCalendarController@update']);
    Route::delete('/delete/{id}', ['as' => 'work_calendar.destroy', 'uses' => 'WorkCalendarController@destroy']);
    
});
