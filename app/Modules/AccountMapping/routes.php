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
Route::group(['prefix' => 'account-mapping.', 'namespace' => 'App\Modules\AccountMapping\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'account_mapping.index', 'uses' => 'AccountMappingController@index']);
    Route::any('/search', ['as' => 'account_mapping.search', 'uses' => 'AccountMappingController@search']);
    Route::get('/create', ['as' => 'account_mapping.create', 'uses' => 'AccountMappingController@create']);
    Route::get('/show/{id}', ['as' => 'account_mapping.show', 'uses' => 'AccountMappingController@show']);
    Route::get('/preview/{id}', ['as' => 'account_mapping.preview', 'uses' => 'AccountMappingController@preview']);
    Route::get('/print/{id}', ['as' => 'account_mapping.print', 'uses' => 'AccountMappingController@print']);

    Route::post('/store', ['as' => 'account_mapping.store', 'uses' => 'AccountMappingController@store']);
    Route::get('/edit/{id}', ['as' => 'account_mapping.edit', 'uses' => 'AccountMappingController@edit']);
    Route::put('/update/{id}', ['as' => 'account_mapping.update', 'uses' => 'AccountMappingController@update']);
    Route::delete('/delete/{id}', ['as' => 'account_mapping.destroy', 'uses' => 'AccountMappingController@destroy']);
    
});
