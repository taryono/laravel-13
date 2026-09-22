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
Route::group(['prefix' => 'employee-type', 'namespace' => 'App\Modules\EmployeeType\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'employee_type.index', 'uses' => 'EmployeeTypeController@index']);
    Route::any('/search', ['as' => 'employee_type.search', 'uses' => 'EmployeeTypeController@search']);
    Route::get('/create', ['as' => 'employee_type.create', 'uses' => 'EmployeeTypeController@create']);
    Route::get('/show/{id}', ['as' => 'employee_type.show', 'uses' => 'EmployeeTypeController@show']);
    Route::get('/preview/{id}', ['as' => 'employee_type.preview', 'uses' => 'EmployeeTypeController@preview']);
    Route::get('/print/{id}', ['as' => 'employee_type.print', 'uses' => 'EmployeeTypeController@print']);

    Route::post('/store', ['as' => 'employee_type.store', 'uses' => 'EmployeeTypeController@store']);
    Route::get('/edit/{id}', ['as' => 'employee_type.edit', 'uses' => 'EmployeeTypeController@edit']);
    Route::put('/update/{id}', ['as' => 'employee_type.update', 'uses' => 'EmployeeTypeController@update']);
    Route::delete('/delete/{id}', ['as' => 'employee_type.destroy', 'uses' => 'EmployeeTypeController@destroy']);
    
});
