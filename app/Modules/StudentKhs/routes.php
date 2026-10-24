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
Route::group(['prefix' => 'student-khs', 'namespace' => 'App\Modules\StudentKhs\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_khs.index', 'uses' => 'StudentKhsController@index']);
    Route::any('/search', ['as' => 'student_khs.search', 'uses' => 'StudentKhsController@search']);
    Route::get('/create', ['as' => 'student_khs.create', 'uses' => 'StudentKhsController@create']);
    Route::get('/show/{id}', ['as' => 'student_khs.show', 'uses' => 'StudentKhsController@show']);
    Route::get('/preview/{id}', ['as' => 'student_khs.preview', 'uses' => 'StudentKhsController@preview']);
    Route::get('/print/{id}', ['as' => 'student_khs.print', 'uses' => 'StudentKhsController@print']);

    Route::post('/store', ['as' => 'student_khs.store', 'uses' => 'StudentKhsController@store']);
    Route::get('/edit/{id}', ['as' => 'student_khs.edit', 'uses' => 'StudentKhsController@edit']);
    Route::put('/update/{id}', ['as' => 'student_khs.update', 'uses' => 'StudentKhsController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_khs.destroy', 'uses' => 'StudentKhsController@destroy']);
    
});
