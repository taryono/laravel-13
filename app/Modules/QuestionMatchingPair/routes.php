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
Route::group(['prefix' => 'question-matching-pair', 'namespace' => 'App\Modules\QuestionMatchingPair\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'question_matching_pair.index', 'uses' => 'QuestionMatchingPairController@index']);
    Route::any('/search', ['as' => 'question_matching_pair.search', 'uses' => 'QuestionMatchingPairController@search']);
    Route::get('/create', ['as' => 'question_matching_pair.create', 'uses' => 'QuestionMatchingPairController@create']);
    Route::get('/show/{id}', ['as' => 'question_matching_pair.show', 'uses' => 'QuestionMatchingPairController@show']);
    Route::get('/preview/{id}', ['as' => 'question_matching_pair.preview', 'uses' => 'QuestionMatchingPairController@preview']);
    Route::get('/print/{id}', ['as' => 'question_matching_pair.print', 'uses' => 'QuestionMatchingPairController@print']);

    Route::post('/store', ['as' => 'question_matching_pair.store', 'uses' => 'QuestionMatchingPairController@store']);
    Route::get('/edit/{id}', ['as' => 'question_matching_pair.edit', 'uses' => 'QuestionMatchingPairController@edit']);
    Route::put('/update/{id}', ['as' => 'question_matching_pair.update', 'uses' => 'QuestionMatchingPairController@update']);
    Route::delete('/delete/{id}', ['as' => 'question_matching_pair.destroy', 'uses' => 'QuestionMatchingPairController@destroy']);
    
});
