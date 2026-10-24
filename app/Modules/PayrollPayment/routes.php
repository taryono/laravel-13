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
Route::group(['prefix' => 'payroll-payment', 'namespace' => 'App\Modules\PayrollPayment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payroll_payment.index', 'uses' => 'PayrollPaymentController@index']);
    Route::any('/search', ['as' => 'payroll_payment.search', 'uses' => 'PayrollPaymentController@search']);
    Route::get('/create', ['as' => 'payroll_payment.create', 'uses' => 'PayrollPaymentController@create']);
    Route::get('/show/{id}', ['as' => 'payroll_payment.show', 'uses' => 'PayrollPaymentController@show']);
    Route::get('/preview/{id}', ['as' => 'payroll_payment.preview', 'uses' => 'PayrollPaymentController@preview']);
    Route::get('/print/{id}', ['as' => 'payroll_payment.print', 'uses' => 'PayrollPaymentController@print']);

    Route::post('/store', ['as' => 'payroll_payment.store', 'uses' => 'PayrollPaymentController@store']);
    Route::get('/edit/{id}', ['as' => 'payroll_payment.edit', 'uses' => 'PayrollPaymentController@edit']);
    Route::put('/update/{id}', ['as' => 'payroll_payment.update', 'uses' => 'PayrollPaymentController@update']);
    Route::delete('/delete/{id}', ['as' => 'payroll_payment.destroy', 'uses' => 'PayrollPaymentController@destroy']);
    
});
