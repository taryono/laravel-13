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
Route::group(['prefix' => 'salary-structure-component', 'namespace' => 'App\Modules\SalaryStructureComponent\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'salary_structure_component.index', 'uses' => 'SalaryStructureComponentController@index']);
    Route::any('/search', ['as' => 'salary_structure_component.search', 'uses' => 'SalaryStructureComponentController@search']);
    Route::get('/create', ['as' => 'salary_structure_component.create', 'uses' => 'SalaryStructureComponentController@create']);
    Route::get('/show/{id}', ['as' => 'salary_structure_component.show', 'uses' => 'SalaryStructureComponentController@show']);
    Route::get('/preview/{id}', ['as' => 'salary_structure_component.preview', 'uses' => 'SalaryStructureComponentController@preview']);
    Route::get('/print/{id}', ['as' => 'salary_structure_component.print', 'uses' => 'SalaryStructureComponentController@print']);

    Route::post('/store', ['as' => 'salary_structure_component.store', 'uses' => 'SalaryStructureComponentController@store']);
    Route::get('/edit/{id}', ['as' => 'salary_structure_component.edit', 'uses' => 'SalaryStructureComponentController@edit']);
    Route::put('/update/{id}', ['as' => 'salary_structure_component.update', 'uses' => 'SalaryStructureComponentController@update']);
    Route::delete('/delete/{id}', ['as' => 'salary_structure_component.destroy', 'uses' => 'SalaryStructureComponentController@destroy']);
    
});
