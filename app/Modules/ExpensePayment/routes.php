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
Route::group(['prefix' => 'expense-payment', 'namespace' => 'App\Modules\ExpensePayment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'expense_payment.index', 'uses' => 'ExpensePaymentController@index']);
    Route::any('/search', ['as' => 'expense_payment.search', 'uses' => 'ExpensePaymentController@search']);
    Route::get('/create', ['as' => 'expense_payment.create', 'uses' => 'ExpensePaymentController@create']);
    Route::get('/show/{id}', ['as' => 'expense_payment.show', 'uses' => 'ExpensePaymentController@show']);
    Route::get('/preview/{id}', ['as' => 'expense_payment.preview', 'uses' => 'ExpensePaymentController@preview']);
    Route::get('/print/{id}', ['as' => 'expense_payment.print', 'uses' => 'ExpensePaymentController@print']);

    Route::post('/store', ['as' => 'expense_payment.store', 'uses' => 'ExpensePaymentController@store']);
    Route::get('/edit/{id}', ['as' => 'expense_payment.edit', 'uses' => 'ExpensePaymentController@edit']);
    Route::put('/update/{id}', ['as' => 'expense_payment.update', 'uses' => 'ExpensePaymentController@update']);
    Route::delete('/delete/{id}', ['as' => 'expense_payment.destroy', 'uses' => 'ExpensePaymentController@destroy']);
    
});
