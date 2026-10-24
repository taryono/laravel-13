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
Route::group(['prefix' => 'canteen-order', 'namespace' => 'App\Modules\CanteenOrder\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'canteen_order.index', 'uses' => 'CanteenOrderController@index']);
    Route::any('/search', ['as' => 'canteen_order.search', 'uses' => 'CanteenOrderController@search']);
    Route::get('/create', ['as' => 'canteen_order.create', 'uses' => 'CanteenOrderController@create']);
    Route::get('/show/{id}', ['as' => 'canteen_order.show', 'uses' => 'CanteenOrderController@show']);
    Route::get('/preview/{id}', ['as' => 'canteen_order.preview', 'uses' => 'CanteenOrderController@preview']);
    Route::get('/print/{id}', ['as' => 'canteen_order.print', 'uses' => 'CanteenOrderController@print']);

    Route::post('/store', ['as' => 'canteen_order.store', 'uses' => 'CanteenOrderController@store']);
    Route::get('/edit/{id}', ['as' => 'canteen_order.edit', 'uses' => 'CanteenOrderController@edit']);
    Route::put('/update/{id}', ['as' => 'canteen_order.update', 'uses' => 'CanteenOrderController@update']);
    Route::delete('/delete/{id}', ['as' => 'canteen_order.destroy', 'uses' => 'CanteenOrderController@destroy']);
    
});
