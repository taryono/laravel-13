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
Route::group(['prefix' => 'account', 'namespace' => 'App\Modules\Account\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'account.index', 'uses' => 'AccountController@index']);
    Route::any('/search', ['as' => 'account.search', 'uses' => 'AccountController@search']);
    Route::get('/create', ['as' => 'account.create', 'uses' => 'AccountController@create']);
    Route::get('/show/{id}', ['as' => 'account.show', 'uses' => 'AccountController@show']);
    Route::get('/preview/{id}', ['as' => 'account.preview', 'uses' => 'AccountController@preview']);
    Route::get('/print/{id}', ['as' => 'account.print', 'uses' => 'AccountController@print']);

    Route::post('/store', ['as' => 'account.store', 'uses' => 'AccountController@store']);
    Route::get('/edit/{id}', ['as' => 'account.edit', 'uses' => 'AccountController@edit']);
    Route::put('/update/{id}', ['as' => 'account.update', 'uses' => 'AccountController@update']);
    Route::delete('/delete/{id}', ['as' => 'account.destroy', 'uses' => 'AccountController@destroy']);
    
});
