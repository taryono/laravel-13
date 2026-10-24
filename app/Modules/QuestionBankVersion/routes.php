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
Route::group(['prefix' => 'question-bank-version', 'namespace' => 'App\Modules\QuestionBankVersion\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'question_bank_version.index', 'uses' => 'QuestionBankVersionController@index']);
    Route::any('/search', ['as' => 'question_bank_version.search', 'uses' => 'QuestionBankVersionController@search']);
    Route::get('/create', ['as' => 'question_bank_version.create', 'uses' => 'QuestionBankVersionController@create']);
    Route::get('/show/{id}', ['as' => 'question_bank_version.show', 'uses' => 'QuestionBankVersionController@show']);
    Route::get('/preview/{id}', ['as' => 'question_bank_version.preview', 'uses' => 'QuestionBankVersionController@preview']);
    Route::get('/print/{id}', ['as' => 'question_bank_version.print', 'uses' => 'QuestionBankVersionController@print']);

    Route::post('/store', ['as' => 'question_bank_version.store', 'uses' => 'QuestionBankVersionController@store']);
    Route::get('/edit/{id}', ['as' => 'question_bank_version.edit', 'uses' => 'QuestionBankVersionController@edit']);
    Route::put('/update/{id}', ['as' => 'question_bank_version.update', 'uses' => 'QuestionBankVersionController@update']);
    Route::delete('/delete/{id}', ['as' => 'question_bank_version.destroy', 'uses' => 'QuestionBankVersionController@destroy']);
    
});
