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
Route::group(['prefix' => 'session', 'namespace' => 'App\Modules\Session\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'session.index', 'uses' => 'SessionController@index']);
    Route::any('/search', ['as' => 'session.search', 'uses' => 'SessionController@search']);
    Route::get('/create', ['as' => 'session.create', 'uses' => 'SessionController@create']);
    Route::get('/show/{id}', ['as' => 'session.show', 'uses' => 'SessionController@show']);
    Route::get('/preview/{id}', ['as' => 'session.preview', 'uses' => 'SessionController@preview']);
    Route::get('/print/{id}', ['as' => 'session.print', 'uses' => 'SessionController@print']);

    Route::post('/store', ['as' => 'session.store', 'uses' => 'SessionController@store']);
    Route::get('/edit/{id}', ['as' => 'session.edit', 'uses' => 'SessionController@edit']);
    Route::put('/update/{id}', ['as' => 'session.update', 'uses' => 'SessionController@update']);
    Route::delete('/delete/{id}', ['as' => 'session.destroy', 'uses' => 'SessionController@destroy']);
    
});
