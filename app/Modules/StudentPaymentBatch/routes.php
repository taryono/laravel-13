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
Route::group(['prefix' => 'student-payment-batch', 'namespace' => 'App\Modules\StudentPaymentBatch\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_payment_batch.index', 'uses' => 'StudentPaymentBatchController@index']);
    Route::any('/search', ['as' => 'student_payment_batch.search', 'uses' => 'StudentPaymentBatchController@search']);
    Route::get('/create', ['as' => 'student_payment_batch.create', 'uses' => 'StudentPaymentBatchController@create']);
    Route::get('/show/{id}', ['as' => 'student_payment_batch.show', 'uses' => 'StudentPaymentBatchController@show']);
    Route::get('/preview/{id}', ['as' => 'student_payment_batch.preview', 'uses' => 'StudentPaymentBatchController@preview']);
    Route::get('/print/{id}', ['as' => 'student_payment_batch.print', 'uses' => 'StudentPaymentBatchController@print']);

    Route::post('/store', ['as' => 'student_payment_batch.store', 'uses' => 'StudentPaymentBatchController@store']);
    Route::get('/edit/{id}', ['as' => 'student_payment_batch.edit', 'uses' => 'StudentPaymentBatchController@edit']);
    Route::put('/update/{id}', ['as' => 'student_payment_batch.update', 'uses' => 'StudentPaymentBatchController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_payment_batch.destroy', 'uses' => 'StudentPaymentBatchController@destroy']);
    
});
