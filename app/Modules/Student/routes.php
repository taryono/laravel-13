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
Route::group(['prefix' => 'student', 'namespace' => 'App\Modules\Student\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student.index', 'uses' => 'StudentController@index']);
    Route::any('/search', ['as' => 'student.search', 'uses' => 'StudentController@search']);
    Route::get('/create', ['as' => 'student.create', 'uses' => 'StudentController@create']);
    Route::get('/show/{id}', ['as' => 'student.show', 'uses' => 'StudentController@show']);
    Route::get('/preview/{id}', ['as' => 'student.preview', 'uses' => 'StudentController@preview']);
    Route::get('/print/{id}', ['as' => 'student.print', 'uses' => 'StudentController@print']);

    Route::post('/store', ['as' => 'student.store', 'uses' => 'StudentController@store']);
    Route::get('/edit/{id}', ['as' => 'student.edit', 'uses' => 'StudentController@edit']);
    Route::put('/update/{id}', ['as' => 'student.update', 'uses' => 'StudentController@update']);
    Route::delete('/delete/{id}', ['as' => 'student.destroy', 'uses' => 'StudentController@destroy']);
    
});
