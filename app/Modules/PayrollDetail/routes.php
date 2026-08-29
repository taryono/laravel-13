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
Route::group(['prefix' => 'payroll-detail', 'namespace' => 'App\Modules\PayrollDetail\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payroll_detail.index', 'uses' => 'PayrollDetailController@index']);
    Route::any('/search', ['as' => 'payroll_detail.search', 'uses' => 'PayrollDetailController@search']);
    Route::get('/create', ['as' => 'payroll_detail.create', 'uses' => 'PayrollDetailController@create']);
    Route::get('/show/{id}', ['as' => 'payroll_detail.show', 'uses' => 'PayrollDetailController@show']);
    Route::get('/preview/{id}', ['as' => 'payroll_detail.preview', 'uses' => 'PayrollDetailController@preview']);
    Route::get('/print/{id}', ['as' => 'payroll_detail.print', 'uses' => 'PayrollDetailController@print']);

    Route::post('/store', ['as' => 'payroll_detail.store', 'uses' => 'PayrollDetailController@store']);
    Route::get('/edit/{id}', ['as' => 'payroll_detail.edit', 'uses' => 'PayrollDetailController@edit']);
    Route::put('/update/{id}', ['as' => 'payroll_detail.update', 'uses' => 'PayrollDetailController@update']);
    Route::delete('/delete/{id}', ['as' => 'payroll_detail.destroy', 'uses' => 'PayrollDetailController@destroy']);
    
});
