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
Route::group(['prefix' => 'invoice', 'namespace' => 'App\Modules\Invoice\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'invoice.index', 'uses' => 'InvoiceController@index']);
    Route::any('/search', ['as' => 'invoice.search', 'uses' => 'InvoiceController@search']);
    Route::get('/create', ['as' => 'invoice.create', 'uses' => 'InvoiceController@create']);
    Route::get('/show/{id}', ['as' => 'invoice.show', 'uses' => 'InvoiceController@show']);
    Route::get('/preview/{id}', ['as' => 'invoice.preview', 'uses' => 'InvoiceController@preview']);
    Route::get('/print/{id}', ['as' => 'invoice.print', 'uses' => 'InvoiceController@print']);

    Route::post('/store', ['as' => 'invoice.store', 'uses' => 'InvoiceController@store']);
    Route::get('/edit/{id}', ['as' => 'invoice.edit', 'uses' => 'InvoiceController@edit']);
    Route::put('/update/{id}', ['as' => 'invoice.update', 'uses' => 'InvoiceController@update']);
    Route::delete('/delete/{id}', ['as' => 'invoice.destroy', 'uses' => 'InvoiceController@destroy']);
    
});
