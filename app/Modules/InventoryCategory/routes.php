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
Route::group(['prefix' => 'inventory-category', 'namespace' => 'App\Modules\InventoryCategory\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'inventory_category.index', 'uses' => 'InventoryCategoryController@index']);
    Route::any('/search', ['as' => 'inventory_category.search', 'uses' => 'InventoryCategoryController@search']);
    Route::get('/create', ['as' => 'inventory_category.create', 'uses' => 'InventoryCategoryController@create']);
    Route::get('/show/{id}', ['as' => 'inventory_category.show', 'uses' => 'InventoryCategoryController@show']);
    Route::get('/preview/{id}', ['as' => 'inventory_category.preview', 'uses' => 'InventoryCategoryController@preview']);
    Route::get('/print/{id}', ['as' => 'inventory_category.print', 'uses' => 'InventoryCategoryController@print']);

    Route::post('/store', ['as' => 'inventory_category.store', 'uses' => 'InventoryCategoryController@store']);
    Route::get('/edit/{id}', ['as' => 'inventory_category.edit', 'uses' => 'InventoryCategoryController@edit']);
    Route::put('/update/{id}', ['as' => 'inventory_category.update', 'uses' => 'InventoryCategoryController@update']);
    Route::delete('/delete/{id}', ['as' => 'inventory_category.destroy', 'uses' => 'InventoryCategoryController@destroy']);
    
});
