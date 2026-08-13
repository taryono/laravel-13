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
Route::group(['prefix' => 'room', 'namespace' => 'App\Modules\Room\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'room.index', 'uses' => 'RoomController@index']);
    Route::any('/search', ['as' => 'room.search', 'uses' => 'RoomController@search']);
    Route::get('/create', ['as' => 'room.create', 'uses' => 'RoomController@create']);
    Route::get('/show/{id}', ['as' => 'room.show', 'uses' => 'RoomController@show']);
    Route::get('/preview/{id}', ['as' => 'room.preview', 'uses' => 'RoomController@preview']);
    Route::get('/print/{id}', ['as' => 'room.print', 'uses' => 'RoomController@print']);

    Route::post('/store', ['as' => 'room.store', 'uses' => 'RoomController@store']);
    Route::get('/edit/{id}', ['as' => 'room.edit', 'uses' => 'RoomController@edit']);
    Route::put('/update/{id}', ['as' => 'room.update', 'uses' => 'RoomController@update']);
    Route::delete('/delete/{id}', ['as' => 'room.destroy', 'uses' => 'RoomController@destroy']);
    
});
