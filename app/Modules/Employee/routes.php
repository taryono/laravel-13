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
Route::group(['prefix' => 'employee', 'namespace' => 'App\Modules\Employee\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'employee.index', 'uses' => 'EmployeeController@index']);
    Route::any('/search', ['as' => 'employee.search', 'uses' => 'EmployeeController@search']);
    Route::get('/create', ['as' => 'employee.create', 'uses' => 'EmployeeController@create']);
    Route::get('/show/{id}', ['as' => 'employee.show', 'uses' => 'EmployeeController@show']);
    Route::get('/preview/{id}', ['as' => 'employee.preview', 'uses' => 'EmployeeController@preview']);
    Route::get('/print/{id}', ['as' => 'employee.print', 'uses' => 'EmployeeController@print']);

    Route::post('/store', ['as' => 'employee.store', 'uses' => 'EmployeeController@store']);
    Route::get('/edit/{id}', ['as' => 'employee.edit', 'uses' => 'EmployeeController@edit']);
    Route::put('/update/{id}', ['as' => 'employee.update', 'uses' => 'EmployeeController@update']);
    Route::delete('/delete/{id}', ['as' => 'employee.destroy', 'uses' => 'EmployeeController@destroy']);
    Route::get('/options', ['as' => 'employee.options', 'uses' => 'EmployeeController@options']);
    Route::get('/by-department', ['as' => 'employee.by-department', 'uses' => 'EmployeeController@getByDepartment']);
});
