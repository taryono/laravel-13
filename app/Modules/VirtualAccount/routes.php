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
Route::group(['prefix' => 'virtual-account', 'namespace' => 'App\Modules\VirtualAccount\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'virtual_account.index', 'uses' => 'VirtualAccountController@index']);
    Route::any('/search', ['as' => 'virtual_account.search', 'uses' => 'VirtualAccountController@search']);
    Route::get('/create', ['as' => 'virtual_account.create', 'uses' => 'VirtualAccountController@create']);
    Route::get('/show/{id}', ['as' => 'virtual_account.show', 'uses' => 'VirtualAccountController@show']);
    Route::get('/preview/{id}', ['as' => 'virtual_account.preview', 'uses' => 'VirtualAccountController@preview']);
    Route::get('/print/{id}', ['as' => 'virtual_account.print', 'uses' => 'VirtualAccountController@print']);

    Route::post('/store', ['as' => 'virtual_account.store', 'uses' => 'VirtualAccountController@store']);
    Route::get('/edit/{id}', ['as' => 'virtual_account.edit', 'uses' => 'VirtualAccountController@edit']);
    Route::put('/update/{id}', ['as' => 'virtual_account.update', 'uses' => 'VirtualAccountController@update']);
    Route::delete('/delete/{id}', ['as' => 'virtual_account.destroy', 'uses' => 'VirtualAccountController@destroy']);
    
});
