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
Route::group(['prefix' => 'student-payment', 'namespace' => 'App\Modules\StudentPayment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_payment.index', 'uses' => 'StudentPaymentController@index']);
    Route::any('/search', ['as' => 'student_payment.search', 'uses' => 'StudentPaymentController@search']);
    Route::get('/create', ['as' => 'student_payment.create', 'uses' => 'StudentPaymentController@create']);
    Route::get('/show/{id}', ['as' => 'student_payment.show', 'uses' => 'StudentPaymentController@show']);
    Route::get('/preview/{id}', ['as' => 'student_payment.preview', 'uses' => 'StudentPaymentController@preview']);
    Route::get('/print/{id}', ['as' => 'student_payment.print', 'uses' => 'StudentPaymentController@print']);

    Route::post('/store', ['as' => 'student_payment.store', 'uses' => 'StudentPaymentController@store']);
    Route::get('/edit/{id}', ['as' => 'student_payment.edit', 'uses' => 'StudentPaymentController@edit']);
    Route::put('/update/{id}', ['as' => 'student_payment.update', 'uses' => 'StudentPaymentController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_payment.destroy', 'uses' => 'StudentPaymentController@destroy']);
    
});
