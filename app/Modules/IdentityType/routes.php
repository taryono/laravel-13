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
Route::group(['prefix' => 'identity-type', 'namespace' => 'App\Modules\IdentityType\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'identity_type.index', 'uses' => 'IdentityTypeController@index']);
    Route::any('/search', ['as' => 'identity_type.search', 'uses' => 'IdentityTypeController@search']);
    Route::get('/create', ['as' => 'identity_type.create', 'uses' => 'IdentityTypeController@create']);
    Route::get('/show/{id}', ['as' => 'identity_type.show', 'uses' => 'IdentityTypeController@show']);
    Route::get('/preview/{id}', ['as' => 'identity_type.preview', 'uses' => 'IdentityTypeController@preview']);
    Route::get('/print/{id}', ['as' => 'identity_type.print', 'uses' => 'IdentityTypeController@print']);

    Route::post('/store', ['as' => 'identity_type.store', 'uses' => 'IdentityTypeController@store']);
    Route::get('/edit/{id}', ['as' => 'identity_type.edit', 'uses' => 'IdentityTypeController@edit']);
    Route::put('/update/{id}', ['as' => 'identity_type.update', 'uses' => 'IdentityTypeController@update']);
    Route::delete('/delete/{id}', ['as' => 'identity_type.destroy', 'uses' => 'IdentityTypeController@destroy']);
    
});
