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
Route::group(['prefix' => 'classroom', 'namespace' => 'App\Modules\Classroom\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'classroom.index', 'uses' => 'ClassroomController@index']);
    Route::any('/search', ['as' => 'classroom.search', 'uses' => 'ClassroomController@search']);
    Route::get('/create', ['as' => 'classroom.create', 'uses' => 'ClassroomController@create']);
    Route::get('/show/{id}', ['as' => 'classroom.show', 'uses' => 'ClassroomController@show']);
    Route::get('/preview/{id}', ['as' => 'classroom.preview', 'uses' => 'ClassroomController@preview']);
    Route::get('/print/{id}', ['as' => 'classroom.print', 'uses' => 'ClassroomController@print']);

    Route::post('/store', ['as' => 'classroom.store', 'uses' => 'ClassroomController@store']);
    Route::get('/edit/{id}', ['as' => 'classroom.edit', 'uses' => 'ClassroomController@edit']);
    Route::put('/update/{id}', ['as' => 'classroom.update', 'uses' => 'ClassroomController@update']);
    Route::delete('/delete/{id}', ['as' => 'classroom.destroy', 'uses' => 'ClassroomController@destroy']);
    
});
