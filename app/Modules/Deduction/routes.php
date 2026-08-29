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
Route::group(['prefix' => 'deduction', 'namespace' => 'App\Modules\Deduction\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'deduction.index', 'uses' => 'DeductionController@index']);
    Route::any('/search', ['as' => 'deduction.search', 'uses' => 'DeductionController@search']);
    Route::get('/create', ['as' => 'deduction.create', 'uses' => 'DeductionController@create']);
    Route::get('/show/{id}', ['as' => 'deduction.show', 'uses' => 'DeductionController@show']);
    Route::get('/preview/{id}', ['as' => 'deduction.preview', 'uses' => 'DeductionController@preview']);
    Route::get('/print/{id}', ['as' => 'deduction.print', 'uses' => 'DeductionController@print']);

    Route::post('/store', ['as' => 'deduction.store', 'uses' => 'DeductionController@store']);
    Route::get('/edit/{id}', ['as' => 'deduction.edit', 'uses' => 'DeductionController@edit']);
    Route::put('/update/{id}', ['as' => 'deduction.update', 'uses' => 'DeductionController@update']);
    Route::delete('/delete/{id}', ['as' => 'deduction.destroy', 'uses' => 'DeductionController@destroy']);
    
});
