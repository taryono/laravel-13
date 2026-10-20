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
Route::group(['prefix' => 'tax-liability', 'namespace' => 'App\Modules\TaxLiability\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'tax_liability.index', 'uses' => 'TaxLiabilityController@index']);
    Route::any('/search', ['as' => 'tax_liability.search', 'uses' => 'TaxLiabilityController@search']);
    Route::get('/create', ['as' => 'tax_liability.create', 'uses' => 'TaxLiabilityController@create']);
    Route::get('/show/{id}', ['as' => 'tax_liability.show', 'uses' => 'TaxLiabilityController@show']);
    Route::get('/preview/{id}', ['as' => 'tax_liability.preview', 'uses' => 'TaxLiabilityController@preview']);
    Route::get('/print/{id}', ['as' => 'tax_liability.print', 'uses' => 'TaxLiabilityController@print']);

    Route::post('/store', ['as' => 'tax_liability.store', 'uses' => 'TaxLiabilityController@store']);
    Route::get('/edit/{id}', ['as' => 'tax_liability.edit', 'uses' => 'TaxLiabilityController@edit']);
    Route::put('/update/{id}', ['as' => 'tax_liability.update', 'uses' => 'TaxLiabilityController@update']);
    Route::delete('/delete/{id}', ['as' => 'tax_liability.destroy', 'uses' => 'TaxLiabilityController@destroy']);
    
});
