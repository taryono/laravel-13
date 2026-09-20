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
Route::group(['prefix' => 'cbt-session-answer', 'namespace' => 'App\Modules\CbtSessionAnswer\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cbt_session_answer.index', 'uses' => 'CbtSessionAnswerController@index']);
    Route::any('/search', ['as' => 'cbt_session_answer.search', 'uses' => 'CbtSessionAnswerController@search']);
    Route::get('/create', ['as' => 'cbt_session_answer.create', 'uses' => 'CbtSessionAnswerController@create']);
    Route::get('/show/{id}', ['as' => 'cbt_session_answer.show', 'uses' => 'CbtSessionAnswerController@show']);
    Route::get('/preview/{id}', ['as' => 'cbt_session_answer.preview', 'uses' => 'CbtSessionAnswerController@preview']);
    Route::get('/print/{id}', ['as' => 'cbt_session_answer.print', 'uses' => 'CbtSessionAnswerController@print']);

    Route::post('/store', ['as' => 'cbt_session_answer.store', 'uses' => 'CbtSessionAnswerController@store']);
    Route::get('/edit/{id}', ['as' => 'cbt_session_answer.edit', 'uses' => 'CbtSessionAnswerController@edit']);
    Route::put('/update/{id}', ['as' => 'cbt_session_answer.update', 'uses' => 'CbtSessionAnswerController@update']);
    Route::delete('/delete/{id}', ['as' => 'cbt_session_answer.destroy', 'uses' => 'CbtSessionAnswerController@destroy']);
    
});
