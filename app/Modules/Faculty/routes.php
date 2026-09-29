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
Route::group(['prefix' => 'faculty', 'namespace' => 'App\Modules\Faculty\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'faculty.index', 'uses' => 'FacultyController@index']);
    Route::any('/search', ['as' => 'faculty.search', 'uses' => 'FacultyController@search']);
    Route::get('/create', ['as' => 'faculty.create', 'uses' => 'FacultyController@create']);
    Route::get('/show/{id}', ['as' => 'faculty.show', 'uses' => 'FacultyController@show']);
    Route::get('/preview/{id}', ['as' => 'faculty.preview', 'uses' => 'FacultyController@preview']);
    Route::get('/print/{id}', ['as' => 'faculty.print', 'uses' => 'FacultyController@print']);

    Route::post('/store', ['as' => 'faculty.store', 'uses' => 'FacultyController@store']);
    Route::get('/edit/{id}', ['as' => 'faculty.edit', 'uses' => 'FacultyController@edit']);
    Route::put('/update/{id}', ['as' => 'faculty.update', 'uses' => 'FacultyController@update']);
    Route::delete('/delete/{id}', ['as' => 'faculty.destroy', 'uses' => 'FacultyController@destroy']);
    
});
