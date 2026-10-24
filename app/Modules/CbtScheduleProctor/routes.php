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
Route::group(['prefix' => 'cbt-schedule-proctor', 'namespace' => 'App\Modules\CbtScheduleProctor\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cbt_schedule_proctor.index', 'uses' => 'CbtScheduleProctorController@index']);
    Route::any('/search', ['as' => 'cbt_schedule_proctor.search', 'uses' => 'CbtScheduleProctorController@search']);
    Route::get('/create', ['as' => 'cbt_schedule_proctor.create', 'uses' => 'CbtScheduleProctorController@create']);
    Route::get('/show/{id}', ['as' => 'cbt_schedule_proctor.show', 'uses' => 'CbtScheduleProctorController@show']);
    Route::get('/preview/{id}', ['as' => 'cbt_schedule_proctor.preview', 'uses' => 'CbtScheduleProctorController@preview']);
    Route::get('/print/{id}', ['as' => 'cbt_schedule_proctor.print', 'uses' => 'CbtScheduleProctorController@print']);

    Route::post('/store', ['as' => 'cbt_schedule_proctor.store', 'uses' => 'CbtScheduleProctorController@store']);
    Route::get('/edit/{id}', ['as' => 'cbt_schedule_proctor.edit', 'uses' => 'CbtScheduleProctorController@edit']);
    Route::put('/update/{id}', ['as' => 'cbt_schedule_proctor.update', 'uses' => 'CbtScheduleProctorController@update']);
    Route::delete('/delete/{id}', ['as' => 'cbt_schedule_proctor.destroy', 'uses' => 'CbtScheduleProctorController@destroy']);
    
});
