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
Route::group(['prefix' => 'scholarship-recipient', 'namespace' => 'App\Modules\ScholarshipRecipient\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'scholarship_recipient.index', 'uses' => 'ScholarshipRecipientController@index']);
    Route::any('/search', ['as' => 'scholarship_recipient.search', 'uses' => 'ScholarshipRecipientController@search']);
    Route::get('/create', ['as' => 'scholarship_recipient.create', 'uses' => 'ScholarshipRecipientController@create']);
    Route::get('/show/{id}', ['as' => 'scholarship_recipient.show', 'uses' => 'ScholarshipRecipientController@show']);
    Route::get('/preview/{id}', ['as' => 'scholarship_recipient.preview', 'uses' => 'ScholarshipRecipientController@preview']);
    Route::get('/print/{id}', ['as' => 'scholarship_recipient.print', 'uses' => 'ScholarshipRecipientController@print']);

    Route::post('/store', ['as' => 'scholarship_recipient.store', 'uses' => 'ScholarshipRecipientController@store']);
    Route::get('/edit/{id}', ['as' => 'scholarship_recipient.edit', 'uses' => 'ScholarshipRecipientController@edit']);
    Route::put('/update/{id}', ['as' => 'scholarship_recipient.update', 'uses' => 'ScholarshipRecipientController@update']);
    Route::delete('/delete/{id}', ['as' => 'scholarship_recipient.destroy', 'uses' => 'ScholarshipRecipientController@destroy']);
    
});
