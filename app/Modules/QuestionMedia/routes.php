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
Route::group(['prefix' => 'question-media', 'namespace' => 'App\Modules\QuestionMedia\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'question_media.index', 'uses' => 'QuestionMediaController@index']);
    Route::any('/search', ['as' => 'question_media.search', 'uses' => 'QuestionMediaController@search']);
    Route::get('/create', ['as' => 'question_media.create', 'uses' => 'QuestionMediaController@create']);
    Route::get('/show/{id}', ['as' => 'question_media.show', 'uses' => 'QuestionMediaController@show']);
    Route::get('/preview/{id}', ['as' => 'question_media.preview', 'uses' => 'QuestionMediaController@preview']);
    Route::get('/print/{id}', ['as' => 'question_media.print', 'uses' => 'QuestionMediaController@print']);

    Route::post('/store', ['as' => 'question_media.store', 'uses' => 'QuestionMediaController@store']);
    Route::get('/edit/{id}', ['as' => 'question_media.edit', 'uses' => 'QuestionMediaController@edit']);
    Route::put('/update/{id}', ['as' => 'question_media.update', 'uses' => 'QuestionMediaController@update']);
    Route::delete('/delete/{id}', ['as' => 'question_media.destroy', 'uses' => 'QuestionMediaController@destroy']);
    
});
