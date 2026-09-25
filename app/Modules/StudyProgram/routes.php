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
Route::group(['prefix' => 'study-program', 'namespace' => 'App\Modules\StudyProgram\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'study_program.index', 'uses' => 'StudyProgramController@index']);
    Route::any('/search', ['as' => 'study_program.search', 'uses' => 'StudyProgramController@search']);
    Route::get('/create', ['as' => 'study_program.create', 'uses' => 'StudyProgramController@create']);
    Route::get('/show/{id}', ['as' => 'study_program.show', 'uses' => 'StudyProgramController@show']);
    Route::get('/preview/{id}', ['as' => 'study_program.preview', 'uses' => 'StudyProgramController@preview']);
    Route::get('/print/{id}', ['as' => 'study_program.print', 'uses' => 'StudyProgramController@print']);

    Route::post('/store', ['as' => 'study_program.store', 'uses' => 'StudyProgramController@store']);
    Route::get('/edit/{id}', ['as' => 'study_program.edit', 'uses' => 'StudyProgramController@edit']);
    Route::put('/update/{id}', ['as' => 'study_program.update', 'uses' => 'StudyProgramController@update']);
    Route::delete('/delete/{id}', ['as' => 'study_program.destroy', 'uses' => 'StudyProgramController@destroy']);
    
});
