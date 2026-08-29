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
Route::group(['prefix' => 'employee-salary-component', 'namespace' => 'App\Modules\EmployeeSalaryComponent\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'employee_salary_component.index', 'uses' => 'EmployeeSalaryComponentController@index']);
    Route::any('/search', ['as' => 'employee_salary_component.search', 'uses' => 'EmployeeSalaryComponentController@search']);
    Route::get('/create', ['as' => 'employee_salary_component.create', 'uses' => 'EmployeeSalaryComponentController@create']);
    Route::get('/show/{id}', ['as' => 'employee_salary_component.show', 'uses' => 'EmployeeSalaryComponentController@show']);
    Route::get('/preview/{id}', ['as' => 'employee_salary_component.preview', 'uses' => 'EmployeeSalaryComponentController@preview']);
    Route::get('/print/{id}', ['as' => 'employee_salary_component.print', 'uses' => 'EmployeeSalaryComponentController@print']);

    Route::post('/store', ['as' => 'employee_salary_component.store', 'uses' => 'EmployeeSalaryComponentController@store']);
    Route::get('/edit/{id}', ['as' => 'employee_salary_component.edit', 'uses' => 'EmployeeSalaryComponentController@edit']);
    Route::put('/update/{id}', ['as' => 'employee_salary_component.update', 'uses' => 'EmployeeSalaryComponentController@update']);
    Route::delete('/delete/{id}', ['as' => 'employee_salary_component.destroy', 'uses' => 'EmployeeSalaryComponentController@destroy']);
    
});
