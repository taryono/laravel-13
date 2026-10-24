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
Route::group(['prefix' => 'payroll-detail-component', 'namespace' => 'App\Modules\PayrollDetailComponent\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payroll_detail_component.index', 'uses' => 'PayrollDetailComponentController@index']);
    Route::any('/search', ['as' => 'payroll_detail_component.search', 'uses' => 'PayrollDetailComponentController@search']);
    Route::get('/create', ['as' => 'payroll_detail_component.create', 'uses' => 'PayrollDetailComponentController@create']);
    Route::get('/show/{id}', ['as' => 'payroll_detail_component.show', 'uses' => 'PayrollDetailComponentController@show']);
    Route::get('/preview/{id}', ['as' => 'payroll_detail_component.preview', 'uses' => 'PayrollDetailComponentController@preview']);
    Route::get('/print/{id}', ['as' => 'payroll_detail_component.print', 'uses' => 'PayrollDetailComponentController@print']);

    Route::post('/store', ['as' => 'payroll_detail_component.store', 'uses' => 'PayrollDetailComponentController@store']);
    Route::get('/edit/{id}', ['as' => 'payroll_detail_component.edit', 'uses' => 'PayrollDetailComponentController@edit']);
    Route::put('/update/{id}', ['as' => 'payroll_detail_component.update', 'uses' => 'PayrollDetailComponentController@update']);
    Route::delete('/delete/{id}', ['as' => 'payroll_detail_component.destroy', 'uses' => 'PayrollDetailComponentController@destroy']);
    
});
