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
Route::group(['prefix' => 'bos-allocation', 'namespace' => 'App\Modules\BosAllocation\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'bos_allocation.index', 'uses' => 'BosAllocationController@index']);
    Route::any('/search', ['as' => 'bos_allocation.search', 'uses' => 'BosAllocationController@search']);
    Route::get('/create', ['as' => 'bos_allocation.create', 'uses' => 'BosAllocationController@create']);
    Route::get('/show/{id}', ['as' => 'bos_allocation.show', 'uses' => 'BosAllocationController@show']);
    Route::get('/preview/{id}', ['as' => 'bos_allocation.preview', 'uses' => 'BosAllocationController@preview']);
    Route::get('/print/{id}', ['as' => 'bos_allocation.print', 'uses' => 'BosAllocationController@print']);

    Route::post('/store', ['as' => 'bos_allocation.store', 'uses' => 'BosAllocationController@store']);
    Route::get('/edit/{id}', ['as' => 'bos_allocation.edit', 'uses' => 'BosAllocationController@edit']);
    Route::put('/update/{id}', ['as' => 'bos_allocation.update', 'uses' => 'BosAllocationController@update']);
    Route::delete('/delete/{id}', ['as' => 'bos_allocation.destroy', 'uses' => 'BosAllocationController@destroy']);
    
});
