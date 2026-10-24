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
Route::group(['prefix' => 'student-range', 'namespace' => 'App\Modules\StudentRange\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_range.index', 'uses' => 'StudentRangeController@index']);
    Route::any('/search', ['as' => 'student_range.search', 'uses' => 'StudentRangeController@search']);
    Route::get('/create', ['as' => 'student_range.create', 'uses' => 'StudentRangeController@create']);
    Route::get('/show/{id}', ['as' => 'student_range.show', 'uses' => 'StudentRangeController@show']);
    Route::get('/preview/{id}', ['as' => 'student_range.preview', 'uses' => 'StudentRangeController@preview']);
    Route::get('/print/{id}', ['as' => 'student_range.print', 'uses' => 'StudentRangeController@print']);

    Route::post('/store', ['as' => 'student_range.store', 'uses' => 'StudentRangeController@store']);
    Route::get('/edit/{id}', ['as' => 'student_range.edit', 'uses' => 'StudentRangeController@edit']);
    Route::put('/update/{id}', ['as' => 'student_range.update', 'uses' => 'StudentRangeController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_range.destroy', 'uses' => 'StudentRangeController@destroy']);
    
});
