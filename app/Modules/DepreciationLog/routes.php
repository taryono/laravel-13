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
Route::group(['prefix' => 'depreciation-log', 'namespace' => 'App\Modules\DepreciationLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'depreciation_log.index', 'uses' => 'DepreciationLogController@index']);
    Route::any('/search', ['as' => 'depreciation_log.search', 'uses' => 'DepreciationLogController@search']);
    Route::get('/create', ['as' => 'depreciation_log.create', 'uses' => 'DepreciationLogController@create']);
    Route::get('/show/{id}', ['as' => 'depreciation_log.show', 'uses' => 'DepreciationLogController@show']);
    Route::get('/preview/{id}', ['as' => 'depreciation_log.preview', 'uses' => 'DepreciationLogController@preview']);
    Route::get('/print/{id}', ['as' => 'depreciation_log.print', 'uses' => 'DepreciationLogController@print']);

    Route::post('/store', ['as' => 'depreciation_log.store', 'uses' => 'DepreciationLogController@store']);
    Route::get('/edit/{id}', ['as' => 'depreciation_log.edit', 'uses' => 'DepreciationLogController@edit']);
    Route::put('/update/{id}', ['as' => 'depreciation_log.update', 'uses' => 'DepreciationLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'depreciation_log.destroy', 'uses' => 'DepreciationLogController@destroy']);
    
});
