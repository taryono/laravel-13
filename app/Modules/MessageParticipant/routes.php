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
Route::group(['prefix' => 'message-participant', 'namespace' => 'App\Modules\MessageParticipant\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'message_participant.index', 'uses' => 'MessageParticipantController@index']);
    Route::any('/search', ['as' => 'message_participant.search', 'uses' => 'MessageParticipantController@search']);
    Route::get('/create', ['as' => 'message_participant.create', 'uses' => 'MessageParticipantController@create']);
    Route::get('/show/{id}', ['as' => 'message_participant.show', 'uses' => 'MessageParticipantController@show']);
    Route::get('/preview/{id}', ['as' => 'message_participant.preview', 'uses' => 'MessageParticipantController@preview']);
    Route::get('/print/{id}', ['as' => 'message_participant.print', 'uses' => 'MessageParticipantController@print']);

    Route::post('/store', ['as' => 'message_participant.store', 'uses' => 'MessageParticipantController@store']);
    Route::get('/edit/{id}', ['as' => 'message_participant.edit', 'uses' => 'MessageParticipantController@edit']);
    Route::put('/update/{id}', ['as' => 'message_participant.update', 'uses' => 'MessageParticipantController@update']);
    Route::delete('/delete/{id}', ['as' => 'message_participant.destroy', 'uses' => 'MessageParticipantController@destroy']);
    
});
