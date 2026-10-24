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
Route::group(['prefix' => 'employee-deduction', 'namespace' => 'App\Modules\EmployeeDeduction\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'employee_deduction.index', 'uses' => 'EmployeeDeductionController@index']);
    Route::any('/search', ['as' => 'employee_deduction.search', 'uses' => 'EmployeeDeductionController@search']);
    Route::get('/create', ['as' => 'employee_deduction.create', 'uses' => 'EmployeeDeductionController@create']);
    Route::get('/show/{id}', ['as' => 'employee_deduction.show', 'uses' => 'EmployeeDeductionController@show']);
    Route::get('/preview/{id}', ['as' => 'employee_deduction.preview', 'uses' => 'EmployeeDeductionController@preview']);
    Route::get('/print/{id}', ['as' => 'employee_deduction.print', 'uses' => 'EmployeeDeductionController@print']);

    Route::post('/store', ['as' => 'employee_deduction.store', 'uses' => 'EmployeeDeductionController@store']);
    Route::get('/edit/{id}', ['as' => 'employee_deduction.edit', 'uses' => 'EmployeeDeductionController@edit']);
    Route::put('/update/{id}', ['as' => 'employee_deduction.update', 'uses' => 'EmployeeDeductionController@update']);
    Route::delete('/delete/{id}', ['as' => 'employee_deduction.destroy', 'uses' => 'EmployeeDeductionController@destroy']);
    
});
