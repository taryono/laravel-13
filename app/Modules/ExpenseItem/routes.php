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
Route::group(['prefix' => 'expense-item', 'namespace' => 'App\Modules\ExpenseItem\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'expense_item.index', 'uses' => 'ExpenseItemController@index']);
    Route::any('/search', ['as' => 'expense_item.search', 'uses' => 'ExpenseItemController@search']);
    Route::get('/create', ['as' => 'expense_item.create', 'uses' => 'ExpenseItemController@create']);
    Route::get('/show/{id}', ['as' => 'expense_item.show', 'uses' => 'ExpenseItemController@show']);
    Route::get('/preview/{id}', ['as' => 'expense_item.preview', 'uses' => 'ExpenseItemController@preview']);
    Route::get('/print/{id}', ['as' => 'expense_item.print', 'uses' => 'ExpenseItemController@print']);

    Route::post('/store', ['as' => 'expense_item.store', 'uses' => 'ExpenseItemController@store']);
    Route::get('/edit/{id}', ['as' => 'expense_item.edit', 'uses' => 'ExpenseItemController@edit']);
    Route::put('/update/{id}', ['as' => 'expense_item.update', 'uses' => 'ExpenseItemController@update']);
    Route::delete('/delete/{id}', ['as' => 'expense_item.destroy', 'uses' => 'ExpenseItemController@destroy']);
    
});
