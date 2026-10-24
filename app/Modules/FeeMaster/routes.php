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
Route::group(['prefix' => 'fee-master', 'namespace' => 'App\Modules\FeeMaster\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'fee_master.index', 'uses' => 'FeeMasterController@index']);
    Route::any('/search', ['as' => 'fee_master.search', 'uses' => 'FeeMasterController@search']);
    Route::get('/create', ['as' => 'fee_master.create', 'uses' => 'FeeMasterController@create']);
    Route::get('/show/{id}', ['as' => 'fee_master.show', 'uses' => 'FeeMasterController@show']);
    Route::get('/preview/{id}', ['as' => 'fee_master.preview', 'uses' => 'FeeMasterController@preview']);
    Route::get('/print/{id}', ['as' => 'fee_master.print', 'uses' => 'FeeMasterController@print']);

    Route::post('/store', ['as' => 'fee_master.store', 'uses' => 'FeeMasterController@store']);
    Route::get('/edit/{id}', ['as' => 'fee_master.edit', 'uses' => 'FeeMasterController@edit']);
    Route::put('/update/{id}', ['as' => 'fee_master.update', 'uses' => 'FeeMasterController@update']);
    Route::delete('/delete/{id}', ['as' => 'fee_master.destroy', 'uses' => 'FeeMasterController@destroy']);
    
});
