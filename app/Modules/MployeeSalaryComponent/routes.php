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
Route::group(['prefix' => 'mployee-salary-component', 'namespace' => 'App\Modules\MployeeSalaryComponent\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'mployee_salary_component.index', 'uses' => 'MployeeSalaryComponentController@index']);
    Route::any('/search', ['as' => 'mployee_salary_component.search', 'uses' => 'MployeeSalaryComponentController@search']);
    Route::get('/create', ['as' => 'mployee_salary_component.create', 'uses' => 'MployeeSalaryComponentController@create']);
    Route::get('/show/{id}', ['as' => 'mployee_salary_component.show', 'uses' => 'MployeeSalaryComponentController@show']);
    Route::get('/preview/{id}', ['as' => 'mployee_salary_component.preview', 'uses' => 'MployeeSalaryComponentController@preview']);
    Route::get('/print/{id}', ['as' => 'mployee_salary_component.print', 'uses' => 'MployeeSalaryComponentController@print']);

    Route::post('/store', ['as' => 'mployee_salary_component.store', 'uses' => 'MployeeSalaryComponentController@store']);
    Route::get('/edit/{id}', ['as' => 'mployee_salary_component.edit', 'uses' => 'MployeeSalaryComponentController@edit']);
    Route::put('/update/{id}', ['as' => 'mployee_salary_component.update', 'uses' => 'MployeeSalaryComponentController@update']);
    Route::delete('/delete/{id}', ['as' => 'mployee_salary_component.destroy', 'uses' => 'MployeeSalaryComponentController@destroy']);
    
});
