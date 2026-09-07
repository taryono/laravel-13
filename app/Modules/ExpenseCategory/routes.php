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
Route::group(['prefix' => 'expense-category', 'namespace' => 'App\Modules\ExpenseCategory\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'expense_category.index', 'uses' => 'ExpenseCategoryController@index']);
    Route::any('/search', ['as' => 'expense_category.search', 'uses' => 'ExpenseCategoryController@search']);
    Route::get('/create', ['as' => 'expense_category.create', 'uses' => 'ExpenseCategoryController@create']);
    Route::get('/show/{id}', ['as' => 'expense_category.show', 'uses' => 'ExpenseCategoryController@show']);
    Route::get('/preview/{id}', ['as' => 'expense_category.preview', 'uses' => 'ExpenseCategoryController@preview']);
    Route::get('/print/{id}', ['as' => 'expense_category.print', 'uses' => 'ExpenseCategoryController@print']);

    Route::post('/store', ['as' => 'expense_category.store', 'uses' => 'ExpenseCategoryController@store']);
    Route::get('/edit/{id}', ['as' => 'expense_category.edit', 'uses' => 'ExpenseCategoryController@edit']);
    Route::put('/update/{id}', ['as' => 'expense_category.update', 'uses' => 'ExpenseCategoryController@update']);
    Route::delete('/delete/{id}', ['as' => 'expense_category.destroy', 'uses' => 'ExpenseCategoryController@destroy']);
    
});
