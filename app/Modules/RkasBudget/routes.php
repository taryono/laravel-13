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
Route::group(['prefix' => 'rkas-budget', 'namespace' => 'App\Modules\RkasBudget\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'rkas_budget.index', 'uses' => 'RkasBudgetController@index']);
    Route::any('/search', ['as' => 'rkas_budget.search', 'uses' => 'RkasBudgetController@search']);
    Route::get('/create', ['as' => 'rkas_budget.create', 'uses' => 'RkasBudgetController@create']);
    Route::get('/show/{id}', ['as' => 'rkas_budget.show', 'uses' => 'RkasBudgetController@show']);
    Route::get('/preview/{id}', ['as' => 'rkas_budget.preview', 'uses' => 'RkasBudgetController@preview']);
    Route::get('/print/{id}', ['as' => 'rkas_budget.print', 'uses' => 'RkasBudgetController@print']);

    Route::post('/store', ['as' => 'rkas_budget.store', 'uses' => 'RkasBudgetController@store']);
    Route::get('/edit/{id}', ['as' => 'rkas_budget.edit', 'uses' => 'RkasBudgetController@edit']);
    Route::put('/update/{id}', ['as' => 'rkas_budget.update', 'uses' => 'RkasBudgetController@update']);
    Route::delete('/delete/{id}', ['as' => 'rkas_budget.destroy', 'uses' => 'RkasBudgetController@destroy']);
    
});
