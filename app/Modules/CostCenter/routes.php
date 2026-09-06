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
Route::group(['prefix' => 'cost-center', 'namespace' => 'App\Modules\CostCenter\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cost_center.index', 'uses' => 'CostCenterController@index']);
    Route::any('/search', ['as' => 'cost_center.search', 'uses' => 'CostCenterController@search']);
    Route::get('/create', ['as' => 'cost_center.create', 'uses' => 'CostCenterController@create']);
    Route::get('/show/{id}', ['as' => 'cost_center.show', 'uses' => 'CostCenterController@show']);
    Route::get('/preview/{id}', ['as' => 'cost_center.preview', 'uses' => 'CostCenterController@preview']);
    Route::get('/print/{id}', ['as' => 'cost_center.print', 'uses' => 'CostCenterController@print']);

    Route::post('/store', ['as' => 'cost_center.store', 'uses' => 'CostCenterController@store']);
    Route::get('/edit/{id}', ['as' => 'cost_center.edit', 'uses' => 'CostCenterController@edit']);
    Route::put('/update/{id}', ['as' => 'cost_center.update', 'uses' => 'CostCenterController@update']);
    Route::delete('/delete/{id}', ['as' => 'cost_center.destroy', 'uses' => 'CostCenterController@destroy']);
    
});
