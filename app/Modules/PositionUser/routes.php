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
Route::group(['prefix' => 'position-user', 'namespace' => 'App\Modules\PositionUser\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'position_user.index', 'uses' => 'PositionUserController@index']);
    Route::any('/search', ['as' => 'position_user.search', 'uses' => 'PositionUserController@search']);
    Route::get('/create', ['as' => 'position_user.create', 'uses' => 'PositionUserController@create']);
    Route::get('/show/{id}', ['as' => 'position_user.show', 'uses' => 'PositionUserController@show']);
    Route::get('/preview/{id}', ['as' => 'position_user.preview', 'uses' => 'PositionUserController@preview']);
    Route::get('/print/{id}', ['as' => 'position_user.print', 'uses' => 'PositionUserController@print']);

    Route::post('/store', ['as' => 'position_user.store', 'uses' => 'PositionUserController@store']);
    Route::get('/edit/{id}', ['as' => 'position_user.edit', 'uses' => 'PositionUserController@edit']);
    Route::put('/update/{id}', ['as' => 'position_user.update', 'uses' => 'PositionUserController@update']);
    Route::delete('/delete/{id}', ['as' => 'position_user.destroy', 'uses' => 'PositionUserController@destroy']);
    
});
