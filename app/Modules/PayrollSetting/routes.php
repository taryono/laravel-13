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
Route::group(['prefix' => 'payroll-setting', 'namespace' => 'App\Modules\PayrollSetting\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payroll_setting.index', 'uses' => 'PayrollSettingController@index']);
    Route::any('/search', ['as' => 'payroll_setting.search', 'uses' => 'PayrollSettingController@search']);
    Route::get('/create', ['as' => 'payroll_setting.create', 'uses' => 'PayrollSettingController@create']);
    Route::get('/show/{id}', ['as' => 'payroll_setting.show', 'uses' => 'PayrollSettingController@show']);
    Route::get('/preview/{id}', ['as' => 'payroll_setting.preview', 'uses' => 'PayrollSettingController@preview']);
    Route::get('/print/{id}', ['as' => 'payroll_setting.print', 'uses' => 'PayrollSettingController@print']);

    Route::post('/store', ['as' => 'payroll_setting.store', 'uses' => 'PayrollSettingController@store']);
    Route::get('/edit/{id}', ['as' => 'payroll_setting.edit', 'uses' => 'PayrollSettingController@edit']);
    Route::put('/update/{id}', ['as' => 'payroll_setting.update', 'uses' => 'PayrollSettingController@update']);
    Route::delete('/delete/{id}', ['as' => 'payroll_setting.destroy', 'uses' => 'PayrollSettingController@destroy']);
    
});
