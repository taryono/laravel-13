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
Route::group(['prefix' => 'asset-depreciation-log', 'namespace' => 'App\Modules\AssetDepreciationLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'asset_depreciation_log.index', 'uses' => 'AssetDepreciationLogController@index']);
    Route::any('/search', ['as' => 'asset_depreciation_log.search', 'uses' => 'AssetDepreciationLogController@search']);
    Route::get('/create', ['as' => 'asset_depreciation_log.create', 'uses' => 'AssetDepreciationLogController@create']);
    Route::get('/show/{id}', ['as' => 'asset_depreciation_log.show', 'uses' => 'AssetDepreciationLogController@show']);
    Route::get('/preview/{id}', ['as' => 'asset_depreciation_log.preview', 'uses' => 'AssetDepreciationLogController@preview']);
    Route::get('/print/{id}', ['as' => 'asset_depreciation_log.print', 'uses' => 'AssetDepreciationLogController@print']);

    Route::post('/store', ['as' => 'asset_depreciation_log.store', 'uses' => 'AssetDepreciationLogController@store']);
    Route::get('/edit/{id}', ['as' => 'asset_depreciation_log.edit', 'uses' => 'AssetDepreciationLogController@edit']);
    Route::put('/update/{id}', ['as' => 'asset_depreciation_log.update', 'uses' => 'AssetDepreciationLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'asset_depreciation_log.destroy', 'uses' => 'AssetDepreciationLogController@destroy']);
    
});
