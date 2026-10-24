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
Route::group(['prefix' => 'learning-outcome', 'namespace' => 'App\Modules\LearningOutcome\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'learning_outcome.index', 'uses' => 'LearningOutcomeController@index']);
    Route::any('/search', ['as' => 'learning_outcome.search', 'uses' => 'LearningOutcomeController@search']);
    Route::get('/create', ['as' => 'learning_outcome.create', 'uses' => 'LearningOutcomeController@create']);
    Route::get('/show/{id}', ['as' => 'learning_outcome.show', 'uses' => 'LearningOutcomeController@show']);
    Route::get('/preview/{id}', ['as' => 'learning_outcome.preview', 'uses' => 'LearningOutcomeController@preview']);
    Route::get('/print/{id}', ['as' => 'learning_outcome.print', 'uses' => 'LearningOutcomeController@print']);

    Route::post('/store', ['as' => 'learning_outcome.store', 'uses' => 'LearningOutcomeController@store']);
    Route::get('/edit/{id}', ['as' => 'learning_outcome.edit', 'uses' => 'LearningOutcomeController@edit']);
    Route::put('/update/{id}', ['as' => 'learning_outcome.update', 'uses' => 'LearningOutcomeController@update']);
    Route::delete('/delete/{id}', ['as' => 'learning_outcome.destroy', 'uses' => 'LearningOutcomeController@destroy']);
    
});
