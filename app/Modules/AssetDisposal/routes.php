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
Route::group(['prefix' => 'asset-disposal', 'namespace' => 'App\Modules\AssetDisposal\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'asset_disposal.index', 'uses' => 'AssetDisposalController@index']);
    Route::any('/search', ['as' => 'asset_disposal.search', 'uses' => 'AssetDisposalController@search']);
    Route::get('/create', ['as' => 'asset_disposal.create', 'uses' => 'AssetDisposalController@create']);
    Route::get('/show/{id}', ['as' => 'asset_disposal.show', 'uses' => 'AssetDisposalController@show']);
    Route::get('/preview/{id}', ['as' => 'asset_disposal.preview', 'uses' => 'AssetDisposalController@preview']);
    Route::get('/print/{id}', ['as' => 'asset_disposal.print', 'uses' => 'AssetDisposalController@print']);

    Route::post('/store', ['as' => 'asset_disposal.store', 'uses' => 'AssetDisposalController@store']);
    Route::get('/edit/{id}', ['as' => 'asset_disposal.edit', 'uses' => 'AssetDisposalController@edit']);
    Route::put('/update/{id}', ['as' => 'asset_disposal.update', 'uses' => 'AssetDisposalController@update']);
    Route::delete('/delete/{id}', ['as' => 'asset_disposal.destroy', 'uses' => 'AssetDisposalController@destroy']);
    
});
