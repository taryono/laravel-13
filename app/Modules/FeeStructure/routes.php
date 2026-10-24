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
Route::group(['prefix' => 'fee-structure', 'namespace' => 'App\Modules\FeeStructure\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'fee_structure.index', 'uses' => 'FeeStructureController@index']);
    Route::any('/search', ['as' => 'fee_structure.search', 'uses' => 'FeeStructureController@search']);
    Route::get('/create', ['as' => 'fee_structure.create', 'uses' => 'FeeStructureController@create']);
    Route::get('/show/{id}', ['as' => 'fee_structure.show', 'uses' => 'FeeStructureController@show']);
    Route::get('/preview/{id}', ['as' => 'fee_structure.preview', 'uses' => 'FeeStructureController@preview']);
    Route::get('/print/{id}', ['as' => 'fee_structure.print', 'uses' => 'FeeStructureController@print']);

    Route::post('/store', ['as' => 'fee_structure.store', 'uses' => 'FeeStructureController@store']);
    Route::get('/edit/{id}', ['as' => 'fee_structure.edit', 'uses' => 'FeeStructureController@edit']);
    Route::put('/update/{id}', ['as' => 'fee_structure.update', 'uses' => 'FeeStructureController@update']);
    Route::delete('/delete/{id}', ['as' => 'fee_structure.destroy', 'uses' => 'FeeStructureController@destroy']);
    
});
