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
    Route::get('/show/[menu]', ['as' => 'menu.show', 'uses' => 'MenuController@show']);
    Route::get('/preview/[menu]', ['as' => 'menu.preview', 'uses' => 'MenuController@preview']);
    Route::get('/print/[menu]', ['as' => 'menu.print', 'uses' => 'MenuController@print']);

    Route::post('/store', ['as' => 'menu.store', 'uses' => 'MenuController@store']);
    Route::get('/edit/[menu]', ['as' => 'menu.edit', 'uses' => 'MenuController@edit']);
    Route::put('/update/[menu]', ['as' => 'menu.update', 'uses' => 'MenuController@update']);
    Route::delete('/delete/[menu]', ['as' => 'menu.destroy', 'uses' => 'MenuController@destroy']);
    
});
