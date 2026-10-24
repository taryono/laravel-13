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
Route::group(['prefix' => 'student-installment', 'namespace' => 'App\Modules\StudentInstallment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_installment.index', 'uses' => 'StudentInstallmentController@index']);
    Route::any('/search', ['as' => 'student_installment.search', 'uses' => 'StudentInstallmentController@search']);
    Route::get('/create', ['as' => 'student_installment.create', 'uses' => 'StudentInstallmentController@create']);
    Route::get('/show/{id}', ['as' => 'student_installment.show', 'uses' => 'StudentInstallmentController@show']);
    Route::get('/preview/{id}', ['as' => 'student_installment.preview', 'uses' => 'StudentInstallmentController@preview']);
    Route::get('/print/{id}', ['as' => 'student_installment.print', 'uses' => 'StudentInstallmentController@print']);

    Route::post('/store', ['as' => 'student_installment.store', 'uses' => 'StudentInstallmentController@store']);
    Route::get('/edit/{id}', ['as' => 'student_installment.edit', 'uses' => 'StudentInstallmentController@edit']);
    Route::put('/update/{id}', ['as' => 'student_installment.update', 'uses' => 'StudentInstallmentController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_installment.destroy', 'uses' => 'StudentInstallmentController@destroy']);
    
});
