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
Route::group(['prefix' => 'room-constraint', 'namespace' => 'App\Modules\RoomConstraint\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'room_constraint.index', 'uses' => 'RoomConstraintController@index']);
    Route::any('/search', ['as' => 'room_constraint.search', 'uses' => 'RoomConstraintController@search']);
    Route::get('/create', ['as' => 'room_constraint.create', 'uses' => 'RoomConstraintController@create']);
    Route::get('/show/{id}', ['as' => 'room_constraint.show', 'uses' => 'RoomConstraintController@show']);
    Route::get('/preview/{id}', ['as' => 'room_constraint.preview', 'uses' => 'RoomConstraintController@preview']);
    Route::get('/print/{id}', ['as' => 'room_constraint.print', 'uses' => 'RoomConstraintController@print']);

    Route::post('/store', ['as' => 'room_constraint.store', 'uses' => 'RoomConstraintController@store']);
    Route::get('/edit/{id}', ['as' => 'room_constraint.edit', 'uses' => 'RoomConstraintController@edit']);
    Route::put('/update/{id}', ['as' => 'room_constraint.update', 'uses' => 'RoomConstraintController@update']);
    Route::delete('/delete/{id}', ['as' => 'room_constraint.destroy', 'uses' => 'RoomConstraintController@destroy']);
    
});
