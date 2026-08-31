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
Route::group(['prefix' => 'employee-shift', 'namespace' => 'App\Modules\EmployeeShift\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'employee_shift.index', 'uses' => 'EmployeeShiftController@index']);
    Route::any('/search', ['as' => 'employee_shift.search', 'uses' => 'EmployeeShiftController@search']);
    Route::get('/create', ['as' => 'employee_shift.create', 'uses' => 'EmployeeShiftController@create']);
    Route::get('/show/{id}', ['as' => 'employee_shift.show', 'uses' => 'EmployeeShiftController@show']);
    Route::get('/preview/{id}', ['as' => 'employee_shift.preview', 'uses' => 'EmployeeShiftController@preview']);
    Route::get('/print/{id}', ['as' => 'employee_shift.print', 'uses' => 'EmployeeShiftController@print']);

    Route::post('/store', ['as' => 'employee_shift.store', 'uses' => 'EmployeeShiftController@store']);
    Route::get('/edit/{id}', ['as' => 'employee_shift.edit', 'uses' => 'EmployeeShiftController@edit']);
    Route::put('/update/{id}', ['as' => 'employee_shift.update', 'uses' => 'EmployeeShiftController@update']);
    Route::delete('/delete/{id}', ['as' => 'employee_shift.destroy', 'uses' => 'EmployeeShiftController@destroy']);
    
});
