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
Route::group(['prefix' => 'dormitory-room', 'namespace' => 'App\Modules\DormitoryRoom\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'dormitory_room.index', 'uses' => 'DormitoryRoomController@index']);
    Route::any('/search', ['as' => 'dormitory_room.search', 'uses' => 'DormitoryRoomController@search']);
    Route::get('/create', ['as' => 'dormitory_room.create', 'uses' => 'DormitoryRoomController@create']);
    Route::get('/show/{id}', ['as' => 'dormitory_room.show', 'uses' => 'DormitoryRoomController@show']);
    Route::get('/preview/{id}', ['as' => 'dormitory_room.preview', 'uses' => 'DormitoryRoomController@preview']);
    Route::get('/print/{id}', ['as' => 'dormitory_room.print', 'uses' => 'DormitoryRoomController@print']);

    Route::post('/store', ['as' => 'dormitory_room.store', 'uses' => 'DormitoryRoomController@store']);
    Route::get('/edit/{id}', ['as' => 'dormitory_room.edit', 'uses' => 'DormitoryRoomController@edit']);
    Route::put('/update/{id}', ['as' => 'dormitory_room.update', 'uses' => 'DormitoryRoomController@update']);
    Route::delete('/delete/{id}', ['as' => 'dormitory_room.destroy', 'uses' => 'DormitoryRoomController@destroy']);
    
});
