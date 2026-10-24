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
Route::group(['prefix' => 'document-number-sequence', 'namespace' => 'App\Modules\DocumentNumberSequence\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'document_number_sequence.index', 'uses' => 'DocumentNumberSequenceController@index']);
    Route::any('/search', ['as' => 'document_number_sequence.search', 'uses' => 'DocumentNumberSequenceController@search']);
    Route::get('/create', ['as' => 'document_number_sequence.create', 'uses' => 'DocumentNumberSequenceController@create']);
    Route::get('/show/{id}', ['as' => 'document_number_sequence.show', 'uses' => 'DocumentNumberSequenceController@show']);
    Route::get('/preview/{id}', ['as' => 'document_number_sequence.preview', 'uses' => 'DocumentNumberSequenceController@preview']);
    Route::get('/print/{id}', ['as' => 'document_number_sequence.print', 'uses' => 'DocumentNumberSequenceController@print']);

    Route::post('/store', ['as' => 'document_number_sequence.store', 'uses' => 'DocumentNumberSequenceController@store']);
    Route::get('/edit/{id}', ['as' => 'document_number_sequence.edit', 'uses' => 'DocumentNumberSequenceController@edit']);
    Route::put('/update/{id}', ['as' => 'document_number_sequence.update', 'uses' => 'DocumentNumberSequenceController@update']);
    Route::delete('/delete/{id}', ['as' => 'document_number_sequence.destroy', 'uses' => 'DocumentNumberSequenceController@destroy']);
    
});
