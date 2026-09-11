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
Route::group(['prefix' => 'academic-calendar-event', 'namespace' => 'App\Modules\AcademicCalendarEvent\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'academic_calendar_event.index', 'uses' => 'AcademicCalendarEventController@index']);
    Route::any('/search', ['as' => 'academic_calendar_event.search', 'uses' => 'AcademicCalendarEventController@search']);
    Route::get('/create', ['as' => 'academic_calendar_event.create', 'uses' => 'AcademicCalendarEventController@create']);
    Route::get('/show/{id}', ['as' => 'academic_calendar_event.show', 'uses' => 'AcademicCalendarEventController@show']);
    Route::get('/preview/{id}', ['as' => 'academic_calendar_event.preview', 'uses' => 'AcademicCalendarEventController@preview']);
    Route::get('/print/{id}', ['as' => 'academic_calendar_event.print', 'uses' => 'AcademicCalendarEventController@print']);

    Route::post('/store', ['as' => 'academic_calendar_event.store', 'uses' => 'AcademicCalendarEventController@store']);
    Route::get('/edit/{id}', ['as' => 'academic_calendar_event.edit', 'uses' => 'AcademicCalendarEventController@edit']);
    Route::put('/update/{id}', ['as' => 'academic_calendar_event.update', 'uses' => 'AcademicCalendarEventController@update']);
    Route::delete('/delete/{id}', ['as' => 'academic_calendar_event.destroy', 'uses' => 'AcademicCalendarEventController@destroy']);
    
});
