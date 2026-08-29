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
Route::group(['prefix' => 'leave-balance', 'namespace' => 'App\Modules\LeaveBalance\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'leave_balance.index', 'uses' => 'LeaveBalanceController@index']);
    Route::any('/search', ['as' => 'leave_balance.search', 'uses' => 'LeaveBalanceController@search']);
    Route::get('/create', ['as' => 'leave_balance.create', 'uses' => 'LeaveBalanceController@create']);
    Route::get('/show/{id}', ['as' => 'leave_balance.show', 'uses' => 'LeaveBalanceController@show']);
    Route::get('/preview/{id}', ['as' => 'leave_balance.preview', 'uses' => 'LeaveBalanceController@preview']);
    Route::get('/print/{id}', ['as' => 'leave_balance.print', 'uses' => 'LeaveBalanceController@print']);

    Route::post('/store', ['as' => 'leave_balance.store', 'uses' => 'LeaveBalanceController@store']);
    Route::get('/edit/{id}', ['as' => 'leave_balance.edit', 'uses' => 'LeaveBalanceController@edit']);
    Route::put('/update/{id}', ['as' => 'leave_balance.update', 'uses' => 'LeaveBalanceController@update']);
    Route::delete('/delete/{id}', ['as' => 'leave_balance.destroy', 'uses' => 'LeaveBalanceController@destroy']);
    
});
