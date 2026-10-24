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
Route::group(['prefix' => 'curriculum-objective', 'namespace' => 'App\Modules\CurriculumObjective\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'curriculum_objective.index', 'uses' => 'CurriculumObjectiveController@index']);
    Route::any('/search', ['as' => 'curriculum_objective.search', 'uses' => 'CurriculumObjectiveController@search']);
    Route::get('/create', ['as' => 'curriculum_objective.create', 'uses' => 'CurriculumObjectiveController@create']);
    Route::get('/show/{id}', ['as' => 'curriculum_objective.show', 'uses' => 'CurriculumObjectiveController@show']);
    Route::get('/preview/{id}', ['as' => 'curriculum_objective.preview', 'uses' => 'CurriculumObjectiveController@preview']);
    Route::get('/print/{id}', ['as' => 'curriculum_objective.print', 'uses' => 'CurriculumObjectiveController@print']);

    Route::post('/store', ['as' => 'curriculum_objective.store', 'uses' => 'CurriculumObjectiveController@store']);
    Route::get('/edit/{id}', ['as' => 'curriculum_objective.edit', 'uses' => 'CurriculumObjectiveController@edit']);
    Route::put('/update/{id}', ['as' => 'curriculum_objective.update', 'uses' => 'CurriculumObjectiveController@update']);
    Route::delete('/delete/{id}', ['as' => 'curriculum_objective.destroy', 'uses' => 'CurriculumObjectiveController@destroy']);
    
});
