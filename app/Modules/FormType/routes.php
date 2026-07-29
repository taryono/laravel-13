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
Route::group(['prefix' => 'form-type', 'namespace' => 'App\Modules\FormType\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'form-type.index', 'uses' => 'FormTypeController@index']);
    Route::any('/search', ['as' => 'form-type.search', 'uses' => 'FormTypeController@search']);
    Route::get('/create', ['as' => 'form-type.create', 'uses' => 'FormTypeController@create']);
    Route::get('/show/{form-type}', ['as' => 'form-type.show', 'uses' => 'FormTypeController@show']);
    Route::get('/preview/{form-type}', ['as' => 'form-type.preview', 'uses' => 'FormTypeController@preview']);
    Route::get('/print/{form-type}', ['as' => 'form-type.print', 'uses' => 'FormTypeController@print']);

    Route::post('/store', ['as' => 'form-type.store', 'uses' => 'FormTypeController@store']);
    Route::get('/edit/{form-type}', ['as' => 'form-type.edit', 'uses' => 'FormTypeController@edit']);
    Route::put('/update/{form-type}', ['as' => 'form-type.update', 'uses' => 'FormTypeController@update']);
    Route::delete('/delete/{form-type}', ['as' => 'form-type.destroy', 'uses' => 'FormTypeController@destroy']);
    
});
