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
Route::group(['prefix' => 'position', 'namespace' => 'App\Modules\Position\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'position.index', 'uses' => 'PositionController@index']);
    Route::any('/search', ['as' => 'position.search', 'uses' => 'PositionController@search']);
    Route::get('/create', ['as' => 'position.create', 'uses' => 'PositionController@create']);
    Route::get('/show/{id}', ['as' => 'position.show', 'uses' => 'PositionController@show']);
    Route::get('/preview/{id}', ['as' => 'position.preview', 'uses' => 'PositionController@preview']);
    Route::get('/print/{id}', ['as' => 'position.print', 'uses' => 'PositionController@print']);

    Route::post('/store', ['as' => 'position.store', 'uses' => 'PositionController@store']);
    Route::get('/edit/{id}', ['as' => 'position.edit', 'uses' => 'PositionController@edit']);
    Route::put('/update/{id}', ['as' => 'position.update', 'uses' => 'PositionController@update']);
    Route::delete('/delete/{id}', ['as' => 'position.destroy', 'uses' => 'PositionController@destroy']);
    
});
