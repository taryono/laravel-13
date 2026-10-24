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
Route::group(['prefix' => 'asset-maintenance', 'namespace' => 'App\Modules\AssetMaintenance\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'asset_maintenance.index', 'uses' => 'AssetMaintenanceController@index']);
    Route::any('/search', ['as' => 'asset_maintenance.search', 'uses' => 'AssetMaintenanceController@search']);
    Route::get('/create', ['as' => 'asset_maintenance.create', 'uses' => 'AssetMaintenanceController@create']);
    Route::get('/show/{id}', ['as' => 'asset_maintenance.show', 'uses' => 'AssetMaintenanceController@show']);
    Route::get('/preview/{id}', ['as' => 'asset_maintenance.preview', 'uses' => 'AssetMaintenanceController@preview']);
    Route::get('/print/{id}', ['as' => 'asset_maintenance.print', 'uses' => 'AssetMaintenanceController@print']);

    Route::post('/store', ['as' => 'asset_maintenance.store', 'uses' => 'AssetMaintenanceController@store']);
    Route::get('/edit/{id}', ['as' => 'asset_maintenance.edit', 'uses' => 'AssetMaintenanceController@edit']);
    Route::put('/update/{id}', ['as' => 'asset_maintenance.update', 'uses' => 'AssetMaintenanceController@update']);
    Route::delete('/delete/{id}', ['as' => 'asset_maintenance.destroy', 'uses' => 'AssetMaintenanceController@destroy']);
    
});
