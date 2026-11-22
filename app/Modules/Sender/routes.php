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
Route::group(['prefix' => 'sender', 'namespace' => 'App\Modules\Sender\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'sender.index', 'uses' => 'SenderController@index']);
    Route::any('/search', ['as' => 'sender.search', 'uses' => 'SenderController@search']);
    Route::get('/create', ['as' => 'sender.create', 'uses' => 'SenderController@create']);
    Route::get('/show/{id}', ['as' => 'sender.show', 'uses' => 'SenderController@show']);
    Route::get('/preview/{id}', ['as' => 'sender.preview', 'uses' => 'SenderController@preview']);
    Route::get('/print/{id}', ['as' => 'sender.print', 'uses' => 'SenderController@print']);

    Route::post('/store', ['as' => 'sender.store', 'uses' => 'SenderController@store']);
    Route::get('/edit/{id}', ['as' => 'sender.edit', 'uses' => 'SenderController@edit']);
    Route::put('/update/{id}', ['as' => 'sender.update', 'uses' => 'SenderController@update']);
    Route::delete('/delete/{id}', ['as' => 'sender.destroy', 'uses' => 'SenderController@destroy']);
    
});
