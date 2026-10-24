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
Route::group(['prefix' => 'library-loan', 'namespace' => 'App\Modules\LibraryLoan\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'library_loan.index', 'uses' => 'LibraryLoanController@index']);
    Route::any('/search', ['as' => 'library_loan.search', 'uses' => 'LibraryLoanController@search']);
    Route::get('/create', ['as' => 'library_loan.create', 'uses' => 'LibraryLoanController@create']);
    Route::get('/show/{id}', ['as' => 'library_loan.show', 'uses' => 'LibraryLoanController@show']);
    Route::get('/preview/{id}', ['as' => 'library_loan.preview', 'uses' => 'LibraryLoanController@preview']);
    Route::get('/print/{id}', ['as' => 'library_loan.print', 'uses' => 'LibraryLoanController@print']);

    Route::post('/store', ['as' => 'library_loan.store', 'uses' => 'LibraryLoanController@store']);
    Route::get('/edit/{id}', ['as' => 'library_loan.edit', 'uses' => 'LibraryLoanController@edit']);
    Route::put('/update/{id}', ['as' => 'library_loan.update', 'uses' => 'LibraryLoanController@update']);
    Route::delete('/delete/{id}', ['as' => 'library_loan.destroy', 'uses' => 'LibraryLoanController@destroy']);
    
});
