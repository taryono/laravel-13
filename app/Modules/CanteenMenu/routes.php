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
Route::group(['prefix' => 'canteen-menu', 'namespace' => 'App\Modules\CanteenMenu\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'canteen_menu.index', 'uses' => 'CanteenMenuController@index']);
    Route::any('/search', ['as' => 'canteen_menu.search', 'uses' => 'CanteenMenuController@search']);
    Route::get('/create', ['as' => 'canteen_menu.create', 'uses' => 'CanteenMenuController@create']);
    Route::get('/show/{id}', ['as' => 'canteen_menu.show', 'uses' => 'CanteenMenuController@show']);
    Route::get('/preview/{id}', ['as' => 'canteen_menu.preview', 'uses' => 'CanteenMenuController@preview']);
    Route::get('/print/{id}', ['as' => 'canteen_menu.print', 'uses' => 'CanteenMenuController@print']);

    Route::post('/store', ['as' => 'canteen_menu.store', 'uses' => 'CanteenMenuController@store']);
    Route::get('/edit/{id}', ['as' => 'canteen_menu.edit', 'uses' => 'CanteenMenuController@edit']);
    Route::put('/update/{id}', ['as' => 'canteen_menu.update', 'uses' => 'CanteenMenuController@update']);
    Route::delete('/delete/{id}', ['as' => 'canteen_menu.destroy', 'uses' => 'CanteenMenuController@destroy']);
    
});
