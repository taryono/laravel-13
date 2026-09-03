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
Route::group(['prefix' => 'asset-depreciation', 'namespace' => 'App\Modules\AssetDepreciation\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'asset_depreciation.index', 'uses' => 'AssetDepreciationController@index']);
    Route::any('/search', ['as' => 'asset_depreciation.search', 'uses' => 'AssetDepreciationController@search']);
    Route::get('/create', ['as' => 'asset_depreciation.create', 'uses' => 'AssetDepreciationController@create']);
    Route::get('/show/{id}', ['as' => 'asset_depreciation.show', 'uses' => 'AssetDepreciationController@show']);
    Route::get('/preview/{id}', ['as' => 'asset_depreciation.preview', 'uses' => 'AssetDepreciationController@preview']);
    Route::get('/print/{id}', ['as' => 'asset_depreciation.print', 'uses' => 'AssetDepreciationController@print']);

    Route::post('/store', ['as' => 'asset_depreciation.store', 'uses' => 'AssetDepreciationController@store']);
    Route::get('/edit/{id}', ['as' => 'asset_depreciation.edit', 'uses' => 'AssetDepreciationController@edit']);
    Route::put('/update/{id}', ['as' => 'asset_depreciation.update', 'uses' => 'AssetDepreciationController@update']);
    Route::delete('/delete/{id}', ['as' => 'asset_depreciation.destroy', 'uses' => 'AssetDepreciationController@destroy']);
    
});
