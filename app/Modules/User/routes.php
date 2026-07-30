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
Route::group(['prefix' => 'user', 'namespace' => 'App\Modules\User\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'user.index', 'uses' => 'UserController@index']);
    Route::any('/search', ['as' => 'user.search', 'uses' => 'UserController@search']);
    Route::get('/create', ['as' => 'user.create', 'uses' => 'UserController@create']);
    Route::get('/show/{user}', ['as' => 'user.show', 'uses' => 'UserController@show']);
    Route::get('/preview/{user}', ['as' => 'user.preview', 'uses' => 'UserController@preview']);
    Route::get('/print/{user}', ['as' => 'user.print', 'uses' => 'UserController@print']);

    Route::post('/store', ['as' => 'user.store', 'uses' => 'UserController@store']);
    Route::get('/edit/{user}', ['as' => 'user.edit', 'uses' => 'UserController@edit']);
    Route::put('/update/{user}', ['as' => 'user.update', 'uses' => 'UserController@update']);
    Route::delete('/delete/{user}', ['as' => 'user.destroy', 'uses' => 'UserController@destroy']);
    
});
