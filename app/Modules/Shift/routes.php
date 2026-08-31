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
Route::group(['prefix' => 'shift', 'namespace' => 'App\Modules\Shift\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'shift.index', 'uses' => 'ShiftController@index']);
    Route::any('/search', ['as' => 'shift.search', 'uses' => 'ShiftController@search']);
    Route::get('/create', ['as' => 'shift.create', 'uses' => 'ShiftController@create']);
    Route::get('/show/{id}', ['as' => 'shift.show', 'uses' => 'ShiftController@show']);
    Route::get('/preview/{id}', ['as' => 'shift.preview', 'uses' => 'ShiftController@preview']);
    Route::get('/print/{id}', ['as' => 'shift.print', 'uses' => 'ShiftController@print']);

    Route::post('/store', ['as' => 'shift.store', 'uses' => 'ShiftController@store']);
    Route::get('/edit/{id}', ['as' => 'shift.edit', 'uses' => 'ShiftController@edit']);
    Route::put('/update/{id}', ['as' => 'shift.update', 'uses' => 'ShiftController@update']);
    Route::delete('/delete/{id}', ['as' => 'shift.destroy', 'uses' => 'ShiftController@destroy']);
    
});
