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
Route::group(['prefix' => 'timetable-generation', 'namespace' => 'App\Modules\TimetableGeneration\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'timetable_generation.index', 'uses' => 'TimetableGenerationController@index']);
    Route::any('/search', ['as' => 'timetable_generation.search', 'uses' => 'TimetableGenerationController@search']);
    Route::get('/create', ['as' => 'timetable_generation.create', 'uses' => 'TimetableGenerationController@create']);
    Route::get('/show/{id}', ['as' => 'timetable_generation.show', 'uses' => 'TimetableGenerationController@show']);
    Route::get('/preview/{id}', ['as' => 'timetable_generation.preview', 'uses' => 'TimetableGenerationController@preview']);
    Route::get('/print/{id}', ['as' => 'timetable_generation.print', 'uses' => 'TimetableGenerationController@print']);

    Route::post('/store', ['as' => 'timetable_generation.store', 'uses' => 'TimetableGenerationController@store']);
    Route::get('/edit/{id}', ['as' => 'timetable_generation.edit', 'uses' => 'TimetableGenerationController@edit']);
    Route::put('/update/{id}', ['as' => 'timetable_generation.update', 'uses' => 'TimetableGenerationController@update']);
    Route::delete('/delete/{id}', ['as' => 'timetable_generation.destroy', 'uses' => 'TimetableGenerationController@destroy']);
    
});
