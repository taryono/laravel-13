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
Route::group(['prefix' => 'tax-type', 'namespace' => 'App\Modules\TaxType\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'tax_type.index', 'uses' => 'TaxTypeController@index']);
    Route::any('/search', ['as' => 'tax_type.search', 'uses' => 'TaxTypeController@search']);
    Route::get('/create', ['as' => 'tax_type.create', 'uses' => 'TaxTypeController@create']);
    Route::get('/show/{id}', ['as' => 'tax_type.show', 'uses' => 'TaxTypeController@show']);
    Route::get('/preview/{id}', ['as' => 'tax_type.preview', 'uses' => 'TaxTypeController@preview']);
    Route::get('/print/{id}', ['as' => 'tax_type.print', 'uses' => 'TaxTypeController@print']);

    Route::post('/store', ['as' => 'tax_type.store', 'uses' => 'TaxTypeController@store']);
    Route::get('/edit/{id}', ['as' => 'tax_type.edit', 'uses' => 'TaxTypeController@edit']);
    Route::put('/update/{id}', ['as' => 'tax_type.update', 'uses' => 'TaxTypeController@update']);
    Route::delete('/delete/{id}', ['as' => 'tax_type.destroy', 'uses' => 'TaxTypeController@destroy']);
    
});
