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
Route::group(['prefix' => 'alumnus', 'namespace' => 'App\Modules\Alumnus\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'alumnus.index', 'uses' => 'AlumnusController@index']);
    Route::any('/search', ['as' => 'alumnus.search', 'uses' => 'AlumnusController@search']);
    Route::get('/create', ['as' => 'alumnus.create', 'uses' => 'AlumnusController@create']);
    Route::get('/show/{id}', ['as' => 'alumnus.show', 'uses' => 'AlumnusController@show']);
    Route::get('/preview/{id}', ['as' => 'alumnus.preview', 'uses' => 'AlumnusController@preview']);
    Route::get('/print/{id}', ['as' => 'alumnus.print', 'uses' => 'AlumnusController@print']);

    Route::post('/store', ['as' => 'alumnus.store', 'uses' => 'AlumnusController@store']);
    Route::get('/edit/{id}', ['as' => 'alumnus.edit', 'uses' => 'AlumnusController@edit']);
    Route::put('/update/{id}', ['as' => 'alumnus.update', 'uses' => 'AlumnusController@update']);
    Route::delete('/delete/{id}', ['as' => 'alumnus.destroy', 'uses' => 'AlumnusController@destroy']);
    
});
