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
Route::group(['prefix' => 'menu', 'namespace' => 'App\Modules\Menu\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'menu.index', 'uses' => 'MenuController@index']);
    Route::any('/search', ['as' => 'menu.search', 'uses' => 'MenuController@search']);
    Route::get('/create', ['as' => 'menu.create', 'uses' => 'MenuController@create']);
    Route::get('/show/{id}', ['as' => 'menu.show', 'uses' => 'MenuController@show']);
    Route::get('/preview/{id}', ['as' => 'menu.preview', 'uses' => 'MenuController@preview']);
    Route::get('/print/{id}', ['as' => 'menu.print', 'uses' => 'MenuController@print']);

    Route::post('/store', ['as' => 'menu.store', 'uses' => 'MenuController@store']);
    Route::get('/edit/{id}', ['as' => 'menu.edit', 'uses' => 'MenuController@edit']);
    Route::put('/update/{id}', ['as' => 'menu.update', 'uses' => 'MenuController@update']);
    Route::delete('/delete/{id}', ['as' => 'menu.destroy', 'uses' => 'MenuController@destroy']);
    
});
