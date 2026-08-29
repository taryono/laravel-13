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
Route::group(['prefix' => 'budget', 'namespace' => 'App\Modules\Budget\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'budget.index', 'uses' => 'BudgetController@index']);
    Route::any('/search', ['as' => 'budget.search', 'uses' => 'BudgetController@search']);
    Route::get('/create', ['as' => 'budget.create', 'uses' => 'BudgetController@create']);
    Route::get('/show/{id}', ['as' => 'budget.show', 'uses' => 'BudgetController@show']);
    Route::get('/preview/{id}', ['as' => 'budget.preview', 'uses' => 'BudgetController@preview']);
    Route::get('/print/{id}', ['as' => 'budget.print', 'uses' => 'BudgetController@print']);

    Route::post('/store', ['as' => 'budget.store', 'uses' => 'BudgetController@store']);
    Route::get('/edit/{id}', ['as' => 'budget.edit', 'uses' => 'BudgetController@edit']);
    Route::put('/update/{id}', ['as' => 'budget.update', 'uses' => 'BudgetController@update']);
    Route::delete('/delete/{id}', ['as' => 'budget.destroy', 'uses' => 'BudgetController@destroy']);
    
});
