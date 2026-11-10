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
Route::group(['prefix' => 'curriculum-assignment', 'namespace' => 'App\Modules\CurriculumAssignment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'curriculum_assignment.index', 'uses' => 'CurriculumAssignmentController@index']);
    Route::any('/search', ['as' => 'curriculum_assignment.search', 'uses' => 'CurriculumAssignmentController@search']);
    Route::get('/create', ['as' => 'curriculum_assignment.create', 'uses' => 'CurriculumAssignmentController@create']);
    Route::get('/show/{id}', ['as' => 'curriculum_assignment.show', 'uses' => 'CurriculumAssignmentController@show']);
    Route::get('/preview/{id}', ['as' => 'curriculum_assignment.preview', 'uses' => 'CurriculumAssignmentController@preview']);
    Route::get('/print/{id}', ['as' => 'curriculum_assignment.print', 'uses' => 'CurriculumAssignmentController@print']);

    Route::post('/store', ['as' => 'curriculum_assignment.store', 'uses' => 'CurriculumAssignmentController@store']);
    Route::get('/edit/{id}', ['as' => 'curriculum_assignment.edit', 'uses' => 'CurriculumAssignmentController@edit']);
    Route::put('/update/{id}', ['as' => 'curriculum_assignment.update', 'uses' => 'CurriculumAssignmentController@update']);
    Route::delete('/delete/{id}', ['as' => 'curriculum_assignment.destroy', 'uses' => 'CurriculumAssignmentController@destroy']);
    
});
