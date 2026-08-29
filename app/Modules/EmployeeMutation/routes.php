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
Route::group(['prefix' => 'employee-mutation', 'namespace' => 'App\Modules\EmployeeMutation\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'employee_mutation.index', 'uses' => 'EmployeeMutationController@index']);
    Route::any('/search', ['as' => 'employee_mutation.search', 'uses' => 'EmployeeMutationController@search']);
    Route::get('/create', ['as' => 'employee_mutation.create', 'uses' => 'EmployeeMutationController@create']);
    Route::get('/show/{id}', ['as' => 'employee_mutation.show', 'uses' => 'EmployeeMutationController@show']);
    Route::get('/preview/{id}', ['as' => 'employee_mutation.preview', 'uses' => 'EmployeeMutationController@preview']);
    Route::get('/print/{id}', ['as' => 'employee_mutation.print', 'uses' => 'EmployeeMutationController@print']);

    Route::post('/store', ['as' => 'employee_mutation.store', 'uses' => 'EmployeeMutationController@store']);
    Route::get('/edit/{id}', ['as' => 'employee_mutation.edit', 'uses' => 'EmployeeMutationController@edit']);
    Route::put('/update/{id}', ['as' => 'employee_mutation.update', 'uses' => 'EmployeeMutationController@update']);
    Route::delete('/delete/{id}', ['as' => 'employee_mutation.destroy', 'uses' => 'EmployeeMutationController@destroy']);
    
});
