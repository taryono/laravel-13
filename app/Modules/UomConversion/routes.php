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
Route::group(['prefix' => 'uom-conversion', 'namespace' => 'App\Modules\UomConversion\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'uom_conversion.index', 'uses' => 'UomConversionController@index']);
    Route::any('/search', ['as' => 'uom_conversion.search', 'uses' => 'UomConversionController@search']);
    Route::get('/create', ['as' => 'uom_conversion.create', 'uses' => 'UomConversionController@create']);
    Route::get('/show/{id}', ['as' => 'uom_conversion.show', 'uses' => 'UomConversionController@show']);
    Route::get('/preview/{id}', ['as' => 'uom_conversion.preview', 'uses' => 'UomConversionController@preview']);
    Route::get('/print/{id}', ['as' => 'uom_conversion.print', 'uses' => 'UomConversionController@print']);

    Route::post('/store', ['as' => 'uom_conversion.store', 'uses' => 'UomConversionController@store']);
    Route::get('/edit/{id}', ['as' => 'uom_conversion.edit', 'uses' => 'UomConversionController@edit']);
    Route::put('/update/{id}', ['as' => 'uom_conversion.update', 'uses' => 'UomConversionController@update']);
    Route::delete('/delete/{id}', ['as' => 'uom_conversion.destroy', 'uses' => 'UomConversionController@destroy']);
    
});
