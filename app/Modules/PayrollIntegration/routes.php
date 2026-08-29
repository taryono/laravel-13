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
Route::group(['prefix' => 'payroll-integration', 'namespace' => 'App\Modules\PayrollIntegration\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payroll_integration.index', 'uses' => 'PayrollIntegrationController@index']);
    Route::any('/search', ['as' => 'payroll_integration.search', 'uses' => 'PayrollIntegrationController@search']);
    Route::get('/create', ['as' => 'payroll_integration.create', 'uses' => 'PayrollIntegrationController@create']);
    Route::get('/show/{id}', ['as' => 'payroll_integration.show', 'uses' => 'PayrollIntegrationController@show']);
    Route::get('/preview/{id}', ['as' => 'payroll_integration.preview', 'uses' => 'PayrollIntegrationController@preview']);
    Route::get('/print/{id}', ['as' => 'payroll_integration.print', 'uses' => 'PayrollIntegrationController@print']);

    Route::post('/store', ['as' => 'payroll_integration.store', 'uses' => 'PayrollIntegrationController@store']);
    Route::get('/edit/{id}', ['as' => 'payroll_integration.edit', 'uses' => 'PayrollIntegrationController@edit']);
    Route::put('/update/{id}', ['as' => 'payroll_integration.update', 'uses' => 'PayrollIntegrationController@update']);
    Route::delete('/delete/{id}', ['as' => 'payroll_integration.destroy', 'uses' => 'PayrollIntegrationController@destroy']);
    
});
