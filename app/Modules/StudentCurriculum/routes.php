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
Route::group(['prefix' => 'student-curriculum', 'namespace' => 'App\Modules\StudentCurriculum\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_curriculum.index', 'uses' => 'StudentCurriculumController@index']);
    Route::any('/search', ['as' => 'student_curriculum.search', 'uses' => 'StudentCurriculumController@search']);
    Route::get('/create', ['as' => 'student_curriculum.create', 'uses' => 'StudentCurriculumController@create']);
    Route::get('/show/{id}', ['as' => 'student_curriculum.show', 'uses' => 'StudentCurriculumController@show']);
    Route::get('/preview/{id}', ['as' => 'student_curriculum.preview', 'uses' => 'StudentCurriculumController@preview']);
    Route::get('/print/{id}', ['as' => 'student_curriculum.print', 'uses' => 'StudentCurriculumController@print']);

    Route::post('/store', ['as' => 'student_curriculum.store', 'uses' => 'StudentCurriculumController@store']);
    Route::get('/edit/{id}', ['as' => 'student_curriculum.edit', 'uses' => 'StudentCurriculumController@edit']);
    Route::put('/update/{id}', ['as' => 'student_curriculum.update', 'uses' => 'StudentCurriculumController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_curriculum.destroy', 'uses' => 'StudentCurriculumController@destroy']);
    
});
