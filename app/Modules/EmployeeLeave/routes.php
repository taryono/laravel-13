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
Route::group(['prefix' => 'employee-leave', 'namespace' => 'App\Modules\EmployeeLeave\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'employee_leave.index', 'uses' => 'EmployeeLeaveController@index']);
    Route::any('/search', ['as' => 'employee_leave.search', 'uses' => 'EmployeeLeaveController@search']);
    Route::get('/create', ['as' => 'employee_leave.create', 'uses' => 'EmployeeLeaveController@create']);
    Route::get('/show/{id}', ['as' => 'employee_leave.show', 'uses' => 'EmployeeLeaveController@show']);
    Route::get('/preview/{id}', ['as' => 'employee_leave.preview', 'uses' => 'EmployeeLeaveController@preview']);
    Route::get('/print/{id}', ['as' => 'employee_leave.print', 'uses' => 'EmployeeLeaveController@print']);

    Route::post('/store', ['as' => 'employee_leave.store', 'uses' => 'EmployeeLeaveController@store']);
    Route::get('/edit/{id}', ['as' => 'employee_leave.edit', 'uses' => 'EmployeeLeaveController@edit']);
    Route::put('/update/{id}', ['as' => 'employee_leave.update', 'uses' => 'EmployeeLeaveController@update']);
    Route::delete('/delete/{id}', ['as' => 'employee_leave.destroy', 'uses' => 'EmployeeLeaveController@destroy']);
    
});
