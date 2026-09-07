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
Route::group(['prefix' => 'shift-swap', 'namespace' => 'App\Modules\ShiftSwap\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'shift_swap.index', 'uses' => 'ShiftSwapController@index']);
    Route::any('/search', ['as' => 'shift_swap.search', 'uses' => 'ShiftSwapController@search']);
    Route::get('/create', ['as' => 'shift_swap.create', 'uses' => 'ShiftSwapController@create']);
    Route::get('/show/{id}', ['as' => 'shift_swap.show', 'uses' => 'ShiftSwapController@show']);
    Route::get('/preview/{id}', ['as' => 'shift_swap.preview', 'uses' => 'ShiftSwapController@preview']);
    Route::get('/print/{id}', ['as' => 'shift_swap.print', 'uses' => 'ShiftSwapController@print']);

    Route::post('/store', ['as' => 'shift_swap.store', 'uses' => 'ShiftSwapController@store']);
    Route::get('/edit/{id}', ['as' => 'shift_swap.edit', 'uses' => 'ShiftSwapController@edit']);
    Route::put('/update/{id}', ['as' => 'shift_swap.update', 'uses' => 'ShiftSwapController@update']);
    Route::delete('/delete/{id}', ['as' => 'shift_swap.destroy', 'uses' => 'ShiftSwapController@destroy']);
    
});
