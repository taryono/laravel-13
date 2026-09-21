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
Route::group(['prefix' => 'student-discipline', 'namespace' => 'App\Modules\StudentDiscipline\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_discipline.index', 'uses' => 'StudentDisciplineController@index']);
    Route::any('/search', ['as' => 'student_discipline.search', 'uses' => 'StudentDisciplineController@search']);
    Route::get('/create', ['as' => 'student_discipline.create', 'uses' => 'StudentDisciplineController@create']);
    Route::get('/show/{id}', ['as' => 'student_discipline.show', 'uses' => 'StudentDisciplineController@show']);
    Route::get('/preview/{id}', ['as' => 'student_discipline.preview', 'uses' => 'StudentDisciplineController@preview']);
    Route::get('/print/{id}', ['as' => 'student_discipline.print', 'uses' => 'StudentDisciplineController@print']);

    Route::post('/store', ['as' => 'student_discipline.store', 'uses' => 'StudentDisciplineController@store']);
    Route::get('/edit/{id}', ['as' => 'student_discipline.edit', 'uses' => 'StudentDisciplineController@edit']);
    Route::put('/update/{id}', ['as' => 'student_discipline.update', 'uses' => 'StudentDisciplineController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_discipline.destroy', 'uses' => 'StudentDisciplineController@destroy']);
    
});
