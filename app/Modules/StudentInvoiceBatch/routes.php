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
Route::group(['prefix' => 'student-invoice-batch', 'namespace' => 'App\Modules\StudentInvoiceBatch\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_invoice_batch.index', 'uses' => 'StudentInvoiceBatchController@index']);
    Route::any('/search', ['as' => 'student_invoice_batch.search', 'uses' => 'StudentInvoiceBatchController@search']);
    Route::get('/create', ['as' => 'student_invoice_batch.create', 'uses' => 'StudentInvoiceBatchController@create']);
    Route::get('/show/{id}', ['as' => 'student_invoice_batch.show', 'uses' => 'StudentInvoiceBatchController@show']);
    Route::get('/preview/{id}', ['as' => 'student_invoice_batch.preview', 'uses' => 'StudentInvoiceBatchController@preview']);
    Route::get('/print/{id}', ['as' => 'student_invoice_batch.print', 'uses' => 'StudentInvoiceBatchController@print']);

    Route::post('/store', ['as' => 'student_invoice_batch.store', 'uses' => 'StudentInvoiceBatchController@store']);
    Route::get('/edit/{id}', ['as' => 'student_invoice_batch.edit', 'uses' => 'StudentInvoiceBatchController@edit']);
    Route::put('/update/{id}', ['as' => 'student_invoice_batch.update', 'uses' => 'StudentInvoiceBatchController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_invoice_batch.destroy', 'uses' => 'StudentInvoiceBatchController@destroy']);
    
});
