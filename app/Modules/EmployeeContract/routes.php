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
Route::group(['prefix' => 'employee-contract', 'namespace' => 'App\Modules\EmployeeContract\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'employee_contract.index', 'uses' => 'EmployeeContractController@index']);
    Route::any('/search', ['as' => 'employee_contract.search', 'uses' => 'EmployeeContractController@search']);
    Route::get('/create', ['as' => 'employee_contract.create', 'uses' => 'EmployeeContractController@create']);
    Route::get('/show/{id}', ['as' => 'employee_contract.show', 'uses' => 'EmployeeContractController@show']);
    Route::get('/preview/{id}', ['as' => 'employee_contract.preview', 'uses' => 'EmployeeContractController@preview']);
    Route::get('/print/{id}', ['as' => 'employee_contract.print', 'uses' => 'EmployeeContractController@print']);

    Route::post('/store', ['as' => 'employee_contract.store', 'uses' => 'EmployeeContractController@store']);
    Route::get('/edit/{id}', ['as' => 'employee_contract.edit', 'uses' => 'EmployeeContractController@edit']);
    Route::put('/update/{id}', ['as' => 'employee_contract.update', 'uses' => 'EmployeeContractController@update']);
    Route::delete('/delete/{id}', ['as' => 'employee_contract.destroy', 'uses' => 'EmployeeContractController@destroy']);
    
});
