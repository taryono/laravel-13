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
Route::group(['prefix' => 'message-thread', 'namespace' => 'App\Modules\MessageThread\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'message_thread.index', 'uses' => 'MessageThreadController@index']);
    Route::any('/search', ['as' => 'message_thread.search', 'uses' => 'MessageThreadController@search']);
    Route::get('/create', ['as' => 'message_thread.create', 'uses' => 'MessageThreadController@create']);
    Route::get('/show/{id}', ['as' => 'message_thread.show', 'uses' => 'MessageThreadController@show']);
    Route::get('/preview/{id}', ['as' => 'message_thread.preview', 'uses' => 'MessageThreadController@preview']);
    Route::get('/print/{id}', ['as' => 'message_thread.print', 'uses' => 'MessageThreadController@print']);

    Route::post('/store', ['as' => 'message_thread.store', 'uses' => 'MessageThreadController@store']);
    Route::get('/edit/{id}', ['as' => 'message_thread.edit', 'uses' => 'MessageThreadController@edit']);
    Route::put('/update/{id}', ['as' => 'message_thread.update', 'uses' => 'MessageThreadController@update']);
    Route::delete('/delete/{id}', ['as' => 'message_thread.destroy', 'uses' => 'MessageThreadController@destroy']);
    
});
