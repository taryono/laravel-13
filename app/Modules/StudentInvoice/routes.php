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
Route::group(['prefix' => 'student-invoice', 'namespace' => 'App\Modules\StudentInvoice\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_invoice.index', 'uses' => 'StudentInvoiceController@index']);
    Route::any('/search', ['as' => 'student_invoice.search', 'uses' => 'StudentInvoiceController@search']);
    Route::get('/create', ['as' => 'student_invoice.create', 'uses' => 'StudentInvoiceController@create']);
    Route::get('/show/{id}', ['as' => 'student_invoice.show', 'uses' => 'StudentInvoiceController@show']);
    Route::get('/preview/{id}', ['as' => 'student_invoice.preview', 'uses' => 'StudentInvoiceController@preview']);
    Route::get('/print/{id}', ['as' => 'student_invoice.print', 'uses' => 'StudentInvoiceController@print']);

    Route::post('/store', ['as' => 'student_invoice.store', 'uses' => 'StudentInvoiceController@store']);
    Route::get('/edit/{id}', ['as' => 'student_invoice.edit', 'uses' => 'StudentInvoiceController@edit']);
    Route::put('/update/{id}', ['as' => 'student_invoice.update', 'uses' => 'StudentInvoiceController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_invoice.destroy', 'uses' => 'StudentInvoiceController@destroy']);
    
});
