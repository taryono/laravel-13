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
Route::group(['prefix' => 'expense', 'namespace' => 'App\Modules\Expense\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'expense.index', 'uses' => 'ExpenseController@index']);
    Route::any('/search', ['as' => 'expense.search', 'uses' => 'ExpenseController@search']);
    Route::get('/create', ['as' => 'expense.create', 'uses' => 'ExpenseController@create']);
    Route::get('/show/{id}', ['as' => 'expense.show', 'uses' => 'ExpenseController@show']);
    Route::get('/preview/{id}', ['as' => 'expense.preview', 'uses' => 'ExpenseController@preview']);
    Route::get('/print/{id}', ['as' => 'expense.print', 'uses' => 'ExpenseController@print']);

    Route::post('/store', ['as' => 'expense.store', 'uses' => 'ExpenseController@store']);
    Route::get('/edit/{id}', ['as' => 'expense.edit', 'uses' => 'ExpenseController@edit']);
    Route::put('/update/{id}', ['as' => 'expense.update', 'uses' => 'ExpenseController@update']);
    Route::delete('/delete/{id}', ['as' => 'expense.destroy', 'uses' => 'ExpenseController@destroy']);
    
});
