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
Route::group(['prefix' => 'student-krs', 'namespace' => 'App\Modules\StudentKrs\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_krs.index', 'uses' => 'StudentKrsController@index']);
    Route::any('/search', ['as' => 'student_krs.search', 'uses' => 'StudentKrsController@search']);
    Route::get('/create', ['as' => 'student_krs.create', 'uses' => 'StudentKrsController@create']);
    Route::get('/show/{id}', ['as' => 'student_krs.show', 'uses' => 'StudentKrsController@show']);
    Route::get('/preview/{id}', ['as' => 'student_krs.preview', 'uses' => 'StudentKrsController@preview']);
    Route::get('/print/{id}', ['as' => 'student_krs.print', 'uses' => 'StudentKrsController@print']);

    Route::post('/store', ['as' => 'student_krs.store', 'uses' => 'StudentKrsController@store']);
    Route::get('/edit/{id}', ['as' => 'student_krs.edit', 'uses' => 'StudentKrsController@edit']);
    Route::put('/update/{id}', ['as' => 'student_krs.update', 'uses' => 'StudentKrsController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_krs.destroy', 'uses' => 'StudentKrsController@destroy']);
    
});
