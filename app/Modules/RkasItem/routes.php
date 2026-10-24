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
Route::group(['prefix' => 'rkas-item', 'namespace' => 'App\Modules\RkasItem\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'rkas_item.index', 'uses' => 'RkasItemController@index']);
    Route::any('/search', ['as' => 'rkas_item.search', 'uses' => 'RkasItemController@search']);
    Route::get('/create', ['as' => 'rkas_item.create', 'uses' => 'RkasItemController@create']);
    Route::get('/show/{id}', ['as' => 'rkas_item.show', 'uses' => 'RkasItemController@show']);
    Route::get('/preview/{id}', ['as' => 'rkas_item.preview', 'uses' => 'RkasItemController@preview']);
    Route::get('/print/{id}', ['as' => 'rkas_item.print', 'uses' => 'RkasItemController@print']);

    Route::post('/store', ['as' => 'rkas_item.store', 'uses' => 'RkasItemController@store']);
    Route::get('/edit/{id}', ['as' => 'rkas_item.edit', 'uses' => 'RkasItemController@edit']);
    Route::put('/update/{id}', ['as' => 'rkas_item.update', 'uses' => 'RkasItemController@update']);
    Route::delete('/delete/{id}', ['as' => 'rkas_item.destroy', 'uses' => 'RkasItemController@destroy']);
    
});
