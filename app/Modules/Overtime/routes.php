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
Route::group(['prefix' => 'overtime', 'namespace' => 'App\Modules\Overtime\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'overtime.index', 'uses' => 'OvertimeController@index']);
    Route::any('/search', ['as' => 'overtime.search', 'uses' => 'OvertimeController@search']);
    Route::get('/create', ['as' => 'overtime.create', 'uses' => 'OvertimeController@create']);
    Route::get('/show/{id}', ['as' => 'overtime.show', 'uses' => 'OvertimeController@show']);
    Route::get('/preview/{id}', ['as' => 'overtime.preview', 'uses' => 'OvertimeController@preview']);
    Route::get('/print/{id}', ['as' => 'overtime.print', 'uses' => 'OvertimeController@print']);

    Route::post('/store', ['as' => 'overtime.store', 'uses' => 'OvertimeController@store']);
    Route::get('/edit/{id}', ['as' => 'overtime.edit', 'uses' => 'OvertimeController@edit']);
    Route::put('/update/{id}', ['as' => 'overtime.update', 'uses' => 'OvertimeController@update']);
    Route::delete('/delete/{id}', ['as' => 'overtime.destroy', 'uses' => 'OvertimeController@destroy']);
    
});
