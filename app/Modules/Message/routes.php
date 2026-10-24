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
Route::group(['prefix' => 'message', 'namespace' => 'App\Modules\Message\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'message.index', 'uses' => 'MessageController@index']);
    Route::any('/search', ['as' => 'message.search', 'uses' => 'MessageController@search']);
    Route::get('/create', ['as' => 'message.create', 'uses' => 'MessageController@create']);
    Route::get('/show/{id}', ['as' => 'message.show', 'uses' => 'MessageController@show']);
    Route::get('/preview/{id}', ['as' => 'message.preview', 'uses' => 'MessageController@preview']);
    Route::get('/print/{id}', ['as' => 'message.print', 'uses' => 'MessageController@print']);

    Route::post('/store', ['as' => 'message.store', 'uses' => 'MessageController@store']);
    Route::get('/edit/{id}', ['as' => 'message.edit', 'uses' => 'MessageController@edit']);
    Route::put('/update/{id}', ['as' => 'message.update', 'uses' => 'MessageController@update']);
    Route::delete('/delete/{id}', ['as' => 'message.destroy', 'uses' => 'MessageController@destroy']);
    
});
