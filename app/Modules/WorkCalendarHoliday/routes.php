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
Route::group(['prefix' => 'work-calendar-holiday', 'namespace' => 'App\Modules\WorkCalendarHoliday\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'work_calendar_holiday.index', 'uses' => 'WorkCalendarHolidayController@index']);
    Route::any('/search', ['as' => 'work_calendar_holiday.search', 'uses' => 'WorkCalendarHolidayController@search']);
    Route::get('/create', ['as' => 'work_calendar_holiday.create', 'uses' => 'WorkCalendarHolidayController@create']);
    Route::get('/show/{id}', ['as' => 'work_calendar_holiday.show', 'uses' => 'WorkCalendarHolidayController@show']);
    Route::get('/preview/{id}', ['as' => 'work_calendar_holiday.preview', 'uses' => 'WorkCalendarHolidayController@preview']);
    Route::get('/print/{id}', ['as' => 'work_calendar_holiday.print', 'uses' => 'WorkCalendarHolidayController@print']);

    Route::post('/store', ['as' => 'work_calendar_holiday.store', 'uses' => 'WorkCalendarHolidayController@store']);
    Route::get('/edit/{id}', ['as' => 'work_calendar_holiday.edit', 'uses' => 'WorkCalendarHolidayController@edit']);
    Route::put('/update/{id}', ['as' => 'work_calendar_holiday.update', 'uses' => 'WorkCalendarHolidayController@update']);
    Route::delete('/delete/{id}', ['as' => 'work_calendar_holiday.destroy', 'uses' => 'WorkCalendarHolidayController@destroy']);
    
});
