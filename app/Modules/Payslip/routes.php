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
Route::group(['prefix' => 'payslip', 'namespace' => 'App\Modules\Payslip\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payslip.index', 'uses' => 'PayslipController@index']);
    Route::any('/search', ['as' => 'payslip.search', 'uses' => 'PayslipController@search']);
    Route::get('/create', ['as' => 'payslip.create', 'uses' => 'PayslipController@create']);
    Route::get('/show/{id}', ['as' => 'payslip.show', 'uses' => 'PayslipController@show']);
    Route::get('/preview/{id}', ['as' => 'payslip.preview', 'uses' => 'PayslipController@preview']);
    Route::get('/print/{id}', ['as' => 'payslip.print', 'uses' => 'PayslipController@print']);

    Route::post('/store', ['as' => 'payslip.store', 'uses' => 'PayslipController@store']);
    Route::get('/edit/{id}', ['as' => 'payslip.edit', 'uses' => 'PayslipController@edit']);
    Route::put('/update/{id}', ['as' => 'payslip.update', 'uses' => 'PayslipController@update']);
    Route::delete('/delete/{id}', ['as' => 'payslip.destroy', 'uses' => 'PayslipController@destroy']);
    
});
