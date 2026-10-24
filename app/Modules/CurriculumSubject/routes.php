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
Route::group(['prefix' => 'curriculum-subject', 'namespace' => 'App\Modules\CurriculumSubject\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'curriculum_subject.index', 'uses' => 'CurriculumSubjectController@index']);
    Route::any('/search', ['as' => 'curriculum_subject.search', 'uses' => 'CurriculumSubjectController@search']);
    Route::get('/create', ['as' => 'curriculum_subject.create', 'uses' => 'CurriculumSubjectController@create']);
    Route::get('/show/{id}', ['as' => 'curriculum_subject.show', 'uses' => 'CurriculumSubjectController@show']);
    Route::get('/preview/{id}', ['as' => 'curriculum_subject.preview', 'uses' => 'CurriculumSubjectController@preview']);
    Route::get('/print/{id}', ['as' => 'curriculum_subject.print', 'uses' => 'CurriculumSubjectController@print']);

    Route::post('/store', ['as' => 'curriculum_subject.store', 'uses' => 'CurriculumSubjectController@store']);
    Route::get('/edit/{id}', ['as' => 'curriculum_subject.edit', 'uses' => 'CurriculumSubjectController@edit']);
    Route::put('/update/{id}', ['as' => 'curriculum_subject.update', 'uses' => 'CurriculumSubjectController@update']);
    Route::delete('/delete/{id}', ['as' => 'curriculum_subject.destroy', 'uses' => 'CurriculumSubjectController@destroy']);
    
});
