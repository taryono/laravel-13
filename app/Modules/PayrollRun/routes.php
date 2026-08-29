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
Route::group(['prefix' => 'payroll-run', 'namespace' => 'App\Modules\PayrollRun\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payroll_run.index', 'uses' => 'PayrollRunController@index']);
    Route::any('/search', ['as' => 'payroll_run.search', 'uses' => 'PayrollRunController@search']);
    Route::get('/create', ['as' => 'payroll_run.create', 'uses' => 'PayrollRunController@create']);
    Route::get('/show/{id}', ['as' => 'payroll_run.show', 'uses' => 'PayrollRunController@show']);
    Route::get('/preview/{id}', ['as' => 'payroll_run.preview', 'uses' => 'PayrollRunController@preview']);
    Route::get('/print/{id}', ['as' => 'payroll_run.print', 'uses' => 'PayrollRunController@print']);

    Route::post('/store', ['as' => 'payroll_run.store', 'uses' => 'PayrollRunController@store']);
    Route::get('/edit/{id}', ['as' => 'payroll_run.edit', 'uses' => 'PayrollRunController@edit']);
    Route::put('/update/{id}', ['as' => 'payroll_run.update', 'uses' => 'PayrollRunController@update']);
    Route::delete('/delete/{id}', ['as' => 'payroll_run.destroy', 'uses' => 'PayrollRunController@destroy']);
    
});
