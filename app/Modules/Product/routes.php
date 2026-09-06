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
Route::group(['prefix' => 'product', 'namespace' => 'App\Modules\Product\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'product.index', 'uses' => 'ProductController@index']);
    Route::any('/search', ['as' => 'product.search', 'uses' => 'ProductController@search']);
    Route::get('/create', ['as' => 'product.create', 'uses' => 'ProductController@create']);
    Route::get('/show/{id}', ['as' => 'product.show', 'uses' => 'ProductController@show']);
    Route::get('/preview/{id}', ['as' => 'product.preview', 'uses' => 'ProductController@preview']);
    Route::get('/print/{id}', ['as' => 'product.print', 'uses' => 'ProductController@print']);

    Route::post('/store', ['as' => 'product.store', 'uses' => 'ProductController@store']);
    Route::get('/edit/{id}', ['as' => 'product.edit', 'uses' => 'ProductController@edit']);
    Route::put('/update/{id}', ['as' => 'product.update', 'uses' => 'ProductController@update']);
    Route::delete('/delete/{id}', ['as' => 'product.destroy', 'uses' => 'ProductController@destroy']);
    
});
