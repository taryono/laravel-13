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
Route::group(['prefix' => 'vendor', 'namespace' => 'App\Modules\Vendor\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'vendor.index', 'uses' => 'VendorController@index']);
    Route::any('/search', ['as' => 'vendor.search', 'uses' => 'VendorController@search']);
    Route::get('/create', ['as' => 'vendor.create', 'uses' => 'VendorController@create']);
    Route::get('/show/{id}', ['as' => 'vendor.show', 'uses' => 'VendorController@show']);
    Route::get('/preview/{id}', ['as' => 'vendor.preview', 'uses' => 'VendorController@preview']);
    Route::get('/print/{id}', ['as' => 'vendor.print', 'uses' => 'VendorController@print']);

    Route::post('/store', ['as' => 'vendor.store', 'uses' => 'VendorController@store']);
    Route::get('/edit/{id}', ['as' => 'vendor.edit', 'uses' => 'VendorController@edit']);
    Route::put('/update/{id}', ['as' => 'vendor.update', 'uses' => 'VendorController@update']);
    Route::delete('/delete/{id}', ['as' => 'vendor.destroy', 'uses' => 'VendorController@destroy']);
    
});
