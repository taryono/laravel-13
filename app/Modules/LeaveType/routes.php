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
Route::group(['prefix' => 'leave-type', 'namespace' => 'App\Modules\LeaveType\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'leave_type.index', 'uses' => 'LeaveTypeController@index']);
    Route::any('/search', ['as' => 'leave_type.search', 'uses' => 'LeaveTypeController@search']);
    Route::get('/create', ['as' => 'leave_type.create', 'uses' => 'LeaveTypeController@create']);
    Route::get('/show/{id}', ['as' => 'leave_type.show', 'uses' => 'LeaveTypeController@show']);
    Route::get('/preview/{id}', ['as' => 'leave_type.preview', 'uses' => 'LeaveTypeController@preview']);
    Route::get('/print/{id}', ['as' => 'leave_type.print', 'uses' => 'LeaveTypeController@print']);

    Route::post('/store', ['as' => 'leave_type.store', 'uses' => 'LeaveTypeController@store']);
    Route::get('/edit/{id}', ['as' => 'leave_type.edit', 'uses' => 'LeaveTypeController@edit']);
    Route::put('/update/{id}', ['as' => 'leave_type.update', 'uses' => 'LeaveTypeController@update']);
    Route::delete('/delete/{id}', ['as' => 'leave_type.destroy', 'uses' => 'LeaveTypeController@destroy']);
    
});
