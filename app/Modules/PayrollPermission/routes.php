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
Route::group(['prefix' => 'payroll-permission', 'namespace' => 'App\Modules\PayrollPermission\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payroll_permission.index', 'uses' => 'PayrollPermissionController@index']);
    Route::any('/search', ['as' => 'payroll_permission.search', 'uses' => 'PayrollPermissionController@search']);
    Route::get('/create', ['as' => 'payroll_permission.create', 'uses' => 'PayrollPermissionController@create']);
    Route::get('/show/{id}', ['as' => 'payroll_permission.show', 'uses' => 'PayrollPermissionController@show']);
    Route::get('/preview/{id}', ['as' => 'payroll_permission.preview', 'uses' => 'PayrollPermissionController@preview']);
    Route::get('/print/{id}', ['as' => 'payroll_permission.print', 'uses' => 'PayrollPermissionController@print']);

    Route::post('/store', ['as' => 'payroll_permission.store', 'uses' => 'PayrollPermissionController@store']);
    Route::get('/edit/{id}', ['as' => 'payroll_permission.edit', 'uses' => 'PayrollPermissionController@edit']);
    Route::put('/update/{id}', ['as' => 'payroll_permission.update', 'uses' => 'PayrollPermissionController@update']);
    Route::delete('/delete/{id}', ['as' => 'payroll_permission.destroy', 'uses' => 'PayrollPermissionController@destroy']);
    
});
