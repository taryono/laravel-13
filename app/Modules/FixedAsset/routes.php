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
Route::group(['prefix' => 'fixed-asset', 'namespace' => 'App\Modules\FixedAsset\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'fixed_asset.index', 'uses' => 'FixedAssetController@index']);
    Route::any('/search', ['as' => 'fixed_asset.search', 'uses' => 'FixedAssetController@search']);
    Route::get('/create', ['as' => 'fixed_asset.create', 'uses' => 'FixedAssetController@create']);
    Route::get('/show/{id}', ['as' => 'fixed_asset.show', 'uses' => 'FixedAssetController@show']);
    Route::get('/preview/{id}', ['as' => 'fixed_asset.preview', 'uses' => 'FixedAssetController@preview']);
    Route::get('/print/{id}', ['as' => 'fixed_asset.print', 'uses' => 'FixedAssetController@print']);

    Route::post('/store', ['as' => 'fixed_asset.store', 'uses' => 'FixedAssetController@store']);
    Route::get('/edit/{id}', ['as' => 'fixed_asset.edit', 'uses' => 'FixedAssetController@edit']);
    Route::put('/update/{id}', ['as' => 'fixed_asset.update', 'uses' => 'FixedAssetController@update']);
    Route::delete('/delete/{id}', ['as' => 'fixed_asset.destroy', 'uses' => 'FixedAssetController@destroy']);
    
});
