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
Route::group(['prefix' => 'library-stock-opname', 'namespace' => 'App\Modules\LibraryStockOpname\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'library_stock_opname.index', 'uses' => 'LibraryStockOpnameController@index']);
    Route::any('/search', ['as' => 'library_stock_opname.search', 'uses' => 'LibraryStockOpnameController@search']);
    Route::get('/create', ['as' => 'library_stock_opname.create', 'uses' => 'LibraryStockOpnameController@create']);
    Route::get('/show/{id}', ['as' => 'library_stock_opname.show', 'uses' => 'LibraryStockOpnameController@show']);
    Route::get('/preview/{id}', ['as' => 'library_stock_opname.preview', 'uses' => 'LibraryStockOpnameController@preview']);
    Route::get('/print/{id}', ['as' => 'library_stock_opname.print', 'uses' => 'LibraryStockOpnameController@print']);

    Route::post('/store', ['as' => 'library_stock_opname.store', 'uses' => 'LibraryStockOpnameController@store']);
    Route::get('/edit/{id}', ['as' => 'library_stock_opname.edit', 'uses' => 'LibraryStockOpnameController@edit']);
    Route::put('/update/{id}', ['as' => 'library_stock_opname.update', 'uses' => 'LibraryStockOpnameController@update']);
    Route::delete('/delete/{id}', ['as' => 'library_stock_opname.destroy', 'uses' => 'LibraryStockOpnameController@destroy']);
    
});
