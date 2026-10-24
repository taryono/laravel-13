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
Route::group(['prefix' => 'budget-detail', 'namespace' => 'App\Modules\BudgetDetail\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'budget_detail.index', 'uses' => 'BudgetDetailController@index']);
    Route::any('/search', ['as' => 'budget_detail.search', 'uses' => 'BudgetDetailController@search']);
    Route::get('/create', ['as' => 'budget_detail.create', 'uses' => 'BudgetDetailController@create']);
    Route::get('/show/{id}', ['as' => 'budget_detail.show', 'uses' => 'BudgetDetailController@show']);
    Route::get('/preview/{id}', ['as' => 'budget_detail.preview', 'uses' => 'BudgetDetailController@preview']);
    Route::get('/print/{id}', ['as' => 'budget_detail.print', 'uses' => 'BudgetDetailController@print']);

    Route::post('/store', ['as' => 'budget_detail.store', 'uses' => 'BudgetDetailController@store']);
    Route::get('/edit/{id}', ['as' => 'budget_detail.edit', 'uses' => 'BudgetDetailController@edit']);
    Route::put('/update/{id}', ['as' => 'budget_detail.update', 'uses' => 'BudgetDetailController@update']);
    Route::delete('/delete/{id}', ['as' => 'budget_detail.destroy', 'uses' => 'BudgetDetailController@destroy']);
    
});
