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
Route::group(['prefix' => 'student-transcript', 'namespace' => 'App\Modules\StudentTranscript\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_transcript.index', 'uses' => 'StudentTranscriptController@index']);
    Route::any('/search', ['as' => 'student_transcript.search', 'uses' => 'StudentTranscriptController@search']);
    Route::get('/create', ['as' => 'student_transcript.create', 'uses' => 'StudentTranscriptController@create']);
    Route::get('/show/{id}', ['as' => 'student_transcript.show', 'uses' => 'StudentTranscriptController@show']);
    Route::get('/preview/{id}', ['as' => 'student_transcript.preview', 'uses' => 'StudentTranscriptController@preview']);
    Route::get('/print/{id}', ['as' => 'student_transcript.print', 'uses' => 'StudentTranscriptController@print']);

    Route::post('/store', ['as' => 'student_transcript.store', 'uses' => 'StudentTranscriptController@store']);
    Route::get('/edit/{id}', ['as' => 'student_transcript.edit', 'uses' => 'StudentTranscriptController@edit']);
    Route::put('/update/{id}', ['as' => 'student_transcript.update', 'uses' => 'StudentTranscriptController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_transcript.destroy', 'uses' => 'StudentTranscriptController@destroy']);
    
});
