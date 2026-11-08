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
Route::group(['prefix' => 'employee-bank-account', 'namespace' => 'App\Modules\EmployeeBankAccount\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'employee_bank_account.index', 'uses' => 'EmployeeBankAccountController@index']);
    Route::any('/search', ['as' => 'employee_bank_account.search', 'uses' => 'EmployeeBankAccountController@search']);
    Route::get('/create', ['as' => 'employee_bank_account.create', 'uses' => 'EmployeeBankAccountController@create']);
    Route::get('/show/{id}', ['as' => 'employee_bank_account.show', 'uses' => 'EmployeeBankAccountController@show']);
    Route::get('/preview/{id}', ['as' => 'employee_bank_account.preview', 'uses' => 'EmployeeBankAccountController@preview']);
    Route::get('/print/{id}', ['as' => 'employee_bank_account.print', 'uses' => 'EmployeeBankAccountController@print']);

    Route::post('/store', ['as' => 'employee_bank_account.store', 'uses' => 'EmployeeBankAccountController@store']);
    Route::get('/edit/{id}', ['as' => 'employee_bank_account.edit', 'uses' => 'EmployeeBankAccountController@edit']);
    Route::put('/update/{id}', ['as' => 'employee_bank_account.update', 'uses' => 'EmployeeBankAccountController@update']);
    Route::delete('/delete/{id}', ['as' => 'employee_bank_account.destroy', 'uses' => 'EmployeeBankAccountController@destroy']);
    
});
