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
Route::group(['prefix' => 'payroll-payment-batch', 'namespace' => 'App\Modules\PayrollPaymentBatch\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payroll_payment_batch.index', 'uses' => 'PayrollPaymentBatchController@index']);
    Route::any('/search', ['as' => 'payroll_payment_batch.search', 'uses' => 'PayrollPaymentBatchController@search']);
    Route::get('/create', ['as' => 'payroll_payment_batch.create', 'uses' => 'PayrollPaymentBatchController@create']);
    Route::get('/show/{id}', ['as' => 'payroll_payment_batch.show', 'uses' => 'PayrollPaymentBatchController@show']);
    Route::get('/preview/{id}', ['as' => 'payroll_payment_batch.preview', 'uses' => 'PayrollPaymentBatchController@preview']);
    Route::get('/print/{id}', ['as' => 'payroll_payment_batch.print', 'uses' => 'PayrollPaymentBatchController@print']);

    Route::post('/store', ['as' => 'payroll_payment_batch.store', 'uses' => 'PayrollPaymentBatchController@store']);
    Route::get('/edit/{id}', ['as' => 'payroll_payment_batch.edit', 'uses' => 'PayrollPaymentBatchController@edit']);
    Route::put('/update/{id}', ['as' => 'payroll_payment_batch.update', 'uses' => 'PayrollPaymentBatchController@update']);
    Route::delete('/delete/{id}', ['as' => 'payroll_payment_batch.destroy', 'uses' => 'PayrollPaymentBatchController@destroy']);
    
});
