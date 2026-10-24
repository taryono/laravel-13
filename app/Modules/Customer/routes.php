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
Route::group(['prefix' => 'customer', 'namespace' => 'App\Modules\Customer\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'customer.index', 'uses' => 'CustomerController@index']);
    Route::any('/search', ['as' => 'customer.search', 'uses' => 'CustomerController@search']);
    Route::get('/create', ['as' => 'customer.create', 'uses' => 'CustomerController@create']);
    Route::get('/show/{id}', ['as' => 'customer.show', 'uses' => 'CustomerController@show']);
    Route::get('/preview/{id}', ['as' => 'customer.preview', 'uses' => 'CustomerController@preview']);
    Route::get('/print/{id}', ['as' => 'customer.print', 'uses' => 'CustomerController@print']);

    Route::post('/store', ['as' => 'customer.store', 'uses' => 'CustomerController@store']);
    Route::get('/edit/{id}', ['as' => 'customer.edit', 'uses' => 'CustomerController@edit']);
    Route::put('/update/{id}', ['as' => 'customer.update', 'uses' => 'CustomerController@update']);
    Route::delete('/delete/{id}', ['as' => 'customer.destroy', 'uses' => 'CustomerController@destroy']);
    
});
