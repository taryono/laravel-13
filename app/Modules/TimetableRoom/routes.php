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
Route::group(['prefix' => 'timetable-room', 'namespace' => 'App\Modules\TimetableRoom\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'timetable_room.index', 'uses' => 'TimetableRoomController@index']);
    Route::any('/search', ['as' => 'timetable_room.search', 'uses' => 'TimetableRoomController@search']);
    Route::get('/create', ['as' => 'timetable_room.create', 'uses' => 'TimetableRoomController@create']);
    Route::get('/show/{id}', ['as' => 'timetable_room.show', 'uses' => 'TimetableRoomController@show']);
    Route::get('/preview/{id}', ['as' => 'timetable_room.preview', 'uses' => 'TimetableRoomController@preview']);
    Route::get('/print/{id}', ['as' => 'timetable_room.print', 'uses' => 'TimetableRoomController@print']);

    Route::post('/store', ['as' => 'timetable_room.store', 'uses' => 'TimetableRoomController@store']);
    Route::get('/edit/{id}', ['as' => 'timetable_room.edit', 'uses' => 'TimetableRoomController@edit']);
    Route::put('/update/{id}', ['as' => 'timetable_room.update', 'uses' => 'TimetableRoomController@update']);
    Route::delete('/delete/{id}', ['as' => 'timetable_room.destroy', 'uses' => 'TimetableRoomController@destroy']);
    
});
