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
Route::group(['prefix' => 'action', 'namespace' => 'App\Modules\Action\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'action.index', 'uses' => 'ActionController@index']);
    Route::any('/search', ['as' => 'action.search', 'uses' => 'ActionController@search']);
    Route::get('/create', ['as' => 'action.create', 'uses' => 'ActionController@create']);
    Route::get('/show/{action}', ['as' => 'action.show', 'uses' => 'ActionController@show']);
    Route::get('/preview/{action}', ['as' => 'action.preview', 'uses' => 'ActionController@preview']);
    Route::get('/print/{action}', ['as' => 'action.print', 'uses' => 'ActionController@print']);

    Route::post('/store', ['as' => 'action.store', 'uses' => 'ActionController@store']);
    Route::get('/edit/{action}', ['as' => 'action.edit', 'uses' => 'ActionController@edit']);
    Route::put('/update/{action}', ['as' => 'action.update', 'uses' => 'ActionController@update']);
    Route::delete('/delete/{action}', ['as' => 'action.destroy', 'uses' => 'ActionController@destroy']);
    
});
