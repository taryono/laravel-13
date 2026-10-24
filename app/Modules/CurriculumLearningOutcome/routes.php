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
Route::group(['prefix' => 'curriculum-learning-outcome', 'namespace' => 'App\Modules\CurriculumLearningOutcome\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'curriculum_learning_outcome.index', 'uses' => 'CurriculumLearningOutcomeController@index']);
    Route::any('/search', ['as' => 'curriculum_learning_outcome.search', 'uses' => 'CurriculumLearningOutcomeController@search']);
    Route::get('/create', ['as' => 'curriculum_learning_outcome.create', 'uses' => 'CurriculumLearningOutcomeController@create']);
    Route::get('/show/{id}', ['as' => 'curriculum_learning_outcome.show', 'uses' => 'CurriculumLearningOutcomeController@show']);
    Route::get('/preview/{id}', ['as' => 'curriculum_learning_outcome.preview', 'uses' => 'CurriculumLearningOutcomeController@preview']);
    Route::get('/print/{id}', ['as' => 'curriculum_learning_outcome.print', 'uses' => 'CurriculumLearningOutcomeController@print']);

    Route::post('/store', ['as' => 'curriculum_learning_outcome.store', 'uses' => 'CurriculumLearningOutcomeController@store']);
    Route::get('/edit/{id}', ['as' => 'curriculum_learning_outcome.edit', 'uses' => 'CurriculumLearningOutcomeController@edit']);
    Route::put('/update/{id}', ['as' => 'curriculum_learning_outcome.update', 'uses' => 'CurriculumLearningOutcomeController@update']);
    Route::delete('/delete/{id}', ['as' => 'curriculum_learning_outcome.destroy', 'uses' => 'CurriculumLearningOutcomeController@destroy']);
    
});
