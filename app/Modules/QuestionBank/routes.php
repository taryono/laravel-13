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
Route::group(['prefix' => 'question-bank', 'namespace' => 'App\Modules\QuestionBank\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'question_bank.index', 'uses' => 'QuestionBankController@index']);
    Route::any('/search', ['as' => 'question_bank.search', 'uses' => 'QuestionBankController@search']);
    Route::get('/create', ['as' => 'question_bank.create', 'uses' => 'QuestionBankController@create']);
    Route::get('/show/{id}', ['as' => 'question_bank.show', 'uses' => 'QuestionBankController@show']);
    Route::get('/preview/{id}', ['as' => 'question_bank.preview', 'uses' => 'QuestionBankController@preview']);
    Route::get('/print/{id}', ['as' => 'question_bank.print', 'uses' => 'QuestionBankController@print']);

    Route::post('/store', ['as' => 'question_bank.store', 'uses' => 'QuestionBankController@store']);
    Route::get('/edit/{id}', ['as' => 'question_bank.edit', 'uses' => 'QuestionBankController@edit']);
    Route::put('/update/{id}', ['as' => 'question_bank.update', 'uses' => 'QuestionBankController@update']);
    Route::delete('/delete/{id}', ['as' => 'question_bank.destroy', 'uses' => 'QuestionBankController@destroy']);
    
});
