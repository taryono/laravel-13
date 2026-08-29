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
Route::group(['prefix' => 'loan-installment', 'namespace' => 'App\Modules\LoanInstallment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'loan_installment.index', 'uses' => 'LoanInstallmentController@index']);
    Route::any('/search', ['as' => 'loan_installment.search', 'uses' => 'LoanInstallmentController@search']);
    Route::get('/create', ['as' => 'loan_installment.create', 'uses' => 'LoanInstallmentController@create']);
    Route::get('/show/{id}', ['as' => 'loan_installment.show', 'uses' => 'LoanInstallmentController@show']);
    Route::get('/preview/{id}', ['as' => 'loan_installment.preview', 'uses' => 'LoanInstallmentController@preview']);
    Route::get('/print/{id}', ['as' => 'loan_installment.print', 'uses' => 'LoanInstallmentController@print']);

    Route::post('/store', ['as' => 'loan_installment.store', 'uses' => 'LoanInstallmentController@store']);
    Route::get('/edit/{id}', ['as' => 'loan_installment.edit', 'uses' => 'LoanInstallmentController@edit']);
    Route::put('/update/{id}', ['as' => 'loan_installment.update', 'uses' => 'LoanInstallmentController@update']);
    Route::delete('/delete/{id}', ['as' => 'loan_installment.destroy', 'uses' => 'LoanInstallmentController@destroy']);
    
});
