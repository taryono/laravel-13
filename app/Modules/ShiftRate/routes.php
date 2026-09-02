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
Route::group(['prefix' => 'shift-rate', 'namespace' => 'App\Modules\ShiftRate\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'shift_rate.index', 'uses' => 'ShiftRateController@index']);
    Route::any('/search', ['as' => 'shift_rate.search', 'uses' => 'ShiftRateController@search']);
    Route::get('/create', ['as' => 'shift_rate.create', 'uses' => 'ShiftRateController@create']);
    Route::get('/show/{id}', ['as' => 'shift_rate.show', 'uses' => 'ShiftRateController@show']);
    Route::get('/preview/{id}', ['as' => 'shift_rate.preview', 'uses' => 'ShiftRateController@preview']);
    Route::get('/print/{id}', ['as' => 'shift_rate.print', 'uses' => 'ShiftRateController@print']);
    Route::post('/store', ['as' => 'shift_rate.store', 'uses' => 'ShiftRateController@store']);
    Route::get('/edit/{id}', ['as' => 'shift_rate.edit', 'uses' => 'ShiftRateController@edit']);
    Route::put('/update/{id}', ['as' => 'shift_rate.update', 'uses' => 'ShiftRateController@update']);
    Route::delete('/delete/{id}', ['as' => 'shift_rate.destroy', 'uses' => 'ShiftRateController@destroy']);
});
