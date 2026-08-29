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
Route::group(['prefix' => 'loan', 'namespace' => 'App\Modules\Loan\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'loan.index', 'uses' => 'LoanController@index']);
    Route::any('/search', ['as' => 'loan.search', 'uses' => 'LoanController@search']);
    Route::get('/create', ['as' => 'loan.create', 'uses' => 'LoanController@create']);
    Route::get('/show/{id}', ['as' => 'loan.show', 'uses' => 'LoanController@show']);
    Route::get('/preview/{id}', ['as' => 'loan.preview', 'uses' => 'LoanController@preview']);
    Route::get('/print/{id}', ['as' => 'loan.print', 'uses' => 'LoanController@print']);

    Route::post('/store', ['as' => 'loan.store', 'uses' => 'LoanController@store']);
    Route::get('/edit/{id}', ['as' => 'loan.edit', 'uses' => 'LoanController@edit']);
    Route::put('/update/{id}', ['as' => 'loan.update', 'uses' => 'LoanController@update']);
    Route::delete('/delete/{id}', ['as' => 'loan.destroy', 'uses' => 'LoanController@destroy']);
    
});
