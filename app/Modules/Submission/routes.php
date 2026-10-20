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
Route::group(['prefix' => 'submission', 'namespace' => 'App\Modules\Submission\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'submission.index', 'uses' => 'SubmissionController@index']);
    Route::any('/search', ['as' => 'submission.search', 'uses' => 'SubmissionController@search']);
    Route::get('/create', ['as' => 'submission.create', 'uses' => 'SubmissionController@create']);
    Route::get('/show/{id}', ['as' => 'submission.show', 'uses' => 'SubmissionController@show']);
    Route::get('/preview/{id}', ['as' => 'submission.preview', 'uses' => 'SubmissionController@preview']);
    Route::get('/print/{id}', ['as' => 'submission.print', 'uses' => 'SubmissionController@print']);

    Route::post('/store', ['as' => 'submission.store', 'uses' => 'SubmissionController@store']);
    Route::get('/edit/{id}', ['as' => 'submission.edit', 'uses' => 'SubmissionController@edit']);
    Route::put('/update/{id}', ['as' => 'submission.update', 'uses' => 'SubmissionController@update']);
    Route::delete('/delete/{id}', ['as' => 'submission.destroy', 'uses' => 'SubmissionController@destroy']);
    
});
