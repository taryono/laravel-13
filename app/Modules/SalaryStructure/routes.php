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
Route::group(['prefix' => 'salary-structure', 'namespace' => 'App\Modules\SalaryStructure\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'salary_structure.index', 'uses' => 'SalaryStructureController@index']);
    Route::any('/search', ['as' => 'salary_structure.search', 'uses' => 'SalaryStructureController@search']);
    Route::get('/create', ['as' => 'salary_structure.create', 'uses' => 'SalaryStructureController@create']);
    Route::get('/show/{id}', ['as' => 'salary_structure.show', 'uses' => 'SalaryStructureController@show']);
    Route::get('/preview/{id}', ['as' => 'salary_structure.preview', 'uses' => 'SalaryStructureController@preview']);
    Route::get('/print/{id}', ['as' => 'salary_structure.print', 'uses' => 'SalaryStructureController@print']);

    Route::post('/store', ['as' => 'salary_structure.store', 'uses' => 'SalaryStructureController@store']);
    Route::get('/edit/{id}', ['as' => 'salary_structure.edit', 'uses' => 'SalaryStructureController@edit']);
    Route::put('/update/{id}', ['as' => 'salary_structure.update', 'uses' => 'SalaryStructureController@update']);
    Route::delete('/delete/{id}', ['as' => 'salary_structure.destroy', 'uses' => 'SalaryStructureController@destroy']);
    
});
