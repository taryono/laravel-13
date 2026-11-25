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
Route::group(['prefix' => 'room-supervisor', 'namespace' => 'App\Modules\RoomSupervisor\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'room_supervisor.index', 'uses' => 'RoomSupervisorController@index']);
    Route::any('/search', ['as' => 'room_supervisor.search', 'uses' => 'RoomSupervisorController@search']);
    Route::get('/create', ['as' => 'room_supervisor.create', 'uses' => 'RoomSupervisorController@create']);
    Route::get('/show/{id}', ['as' => 'room_supervisor.show', 'uses' => 'RoomSupervisorController@show']);
    Route::get('/preview/{id}', ['as' => 'room_supervisor.preview', 'uses' => 'RoomSupervisorController@preview']);
    Route::get('/print/{id}', ['as' => 'room_supervisor.print', 'uses' => 'RoomSupervisorController@print']);

    Route::post('/store', ['as' => 'room_supervisor.store', 'uses' => 'RoomSupervisorController@store']);
    Route::get('/edit/{id}', ['as' => 'room_supervisor.edit', 'uses' => 'RoomSupervisorController@edit']);
    Route::put('/update/{id}', ['as' => 'room_supervisor.update', 'uses' => 'RoomSupervisorController@update']);
    Route::delete('/delete/{id}', ['as' => 'room_supervisor.destroy', 'uses' => 'RoomSupervisorController@destroy']);
    
});
