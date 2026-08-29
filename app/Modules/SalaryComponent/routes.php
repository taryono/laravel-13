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
Route::group(['prefix' => 'salary-component', 'namespace' => 'App\Modules\SalaryComponent\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'salary_component.index', 'uses' => 'SalaryComponentController@index']);
    Route::any('/search', ['as' => 'salary_component.search', 'uses' => 'SalaryComponentController@search']);
    Route::get('/create', ['as' => 'salary_component.create', 'uses' => 'SalaryComponentController@create']);
    Route::get('/show/{id}', ['as' => 'salary_component.show', 'uses' => 'SalaryComponentController@show']);
    Route::get('/preview/{id}', ['as' => 'salary_component.preview', 'uses' => 'SalaryComponentController@preview']);
    Route::get('/print/{id}', ['as' => 'salary_component.print', 'uses' => 'SalaryComponentController@print']);

    Route::post('/store', ['as' => 'salary_component.store', 'uses' => 'SalaryComponentController@store']);
    Route::get('/edit/{id}', ['as' => 'salary_component.edit', 'uses' => 'SalaryComponentController@edit']);
    Route::put('/update/{id}', ['as' => 'salary_component.update', 'uses' => 'SalaryComponentController@update']);
    Route::delete('/delete/{id}', ['as' => 'salary_component.destroy', 'uses' => 'SalaryComponentController@destroy']);
    
});
