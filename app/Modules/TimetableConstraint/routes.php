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
Route::group(['prefix' => 'timetable-constraint', 'namespace' => 'App\Modules\TimetableConstraint\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'timetable_constraint.index', 'uses' => 'TimetableConstraintController@index']);
    Route::any('/search', ['as' => 'timetable_constraint.search', 'uses' => 'TimetableConstraintController@search']);
    Route::get('/create', ['as' => 'timetable_constraint.create', 'uses' => 'TimetableConstraintController@create']);
    Route::get('/show/{id}', ['as' => 'timetable_constraint.show', 'uses' => 'TimetableConstraintController@show']);
    Route::get('/preview/{id}', ['as' => 'timetable_constraint.preview', 'uses' => 'TimetableConstraintController@preview']);
    Route::get('/print/{id}', ['as' => 'timetable_constraint.print', 'uses' => 'TimetableConstraintController@print']);

    Route::post('/store', ['as' => 'timetable_constraint.store', 'uses' => 'TimetableConstraintController@store']);
    Route::get('/edit/{id}', ['as' => 'timetable_constraint.edit', 'uses' => 'TimetableConstraintController@edit']);
    Route::put('/update/{id}', ['as' => 'timetable_constraint.update', 'uses' => 'TimetableConstraintController@update']);
    Route::delete('/delete/{id}', ['as' => 'timetable_constraint.destroy', 'uses' => 'TimetableConstraintController@destroy']);
    
});
