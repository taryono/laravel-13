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
Route::group(['prefix' => 'sorogan-schedule', 'namespace' => 'App\Modules\SoroganSchedule\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'sorogan_schedule.index', 'uses' => 'SoroganScheduleController@index']);
    Route::any('/search', ['as' => 'sorogan_schedule.search', 'uses' => 'SoroganScheduleController@search']);
    Route::get('/create', ['as' => 'sorogan_schedule.create', 'uses' => 'SoroganScheduleController@create']);
    Route::get('/show/{id}', ['as' => 'sorogan_schedule.show', 'uses' => 'SoroganScheduleController@show']);
    Route::get('/preview/{id}', ['as' => 'sorogan_schedule.preview', 'uses' => 'SoroganScheduleController@preview']);
    Route::get('/print/{id}', ['as' => 'sorogan_schedule.print', 'uses' => 'SoroganScheduleController@print']);

    Route::post('/store', ['as' => 'sorogan_schedule.store', 'uses' => 'SoroganScheduleController@store']);
    Route::get('/edit/{id}', ['as' => 'sorogan_schedule.edit', 'uses' => 'SoroganScheduleController@edit']);
    Route::put('/update/{id}', ['as' => 'sorogan_schedule.update', 'uses' => 'SoroganScheduleController@update']);
    Route::delete('/delete/{id}', ['as' => 'sorogan_schedule.destroy', 'uses' => 'SoroganScheduleController@destroy']);
    
});
