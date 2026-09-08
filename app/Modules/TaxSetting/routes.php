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
Route::group(['prefix' => 'tax-setting', 'namespace' => 'App\Modules\TaxSetting\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'tax_setting.index', 'uses' => 'TaxSettingController@index']);
    Route::any('/search', ['as' => 'tax_setting.search', 'uses' => 'TaxSettingController@search']);
    Route::get('/create', ['as' => 'tax_setting.create', 'uses' => 'TaxSettingController@create']);
    Route::get('/show/{id}', ['as' => 'tax_setting.show', 'uses' => 'TaxSettingController@show']);
    Route::get('/preview/{id}', ['as' => 'tax_setting.preview', 'uses' => 'TaxSettingController@preview']);
    Route::get('/print/{id}', ['as' => 'tax_setting.print', 'uses' => 'TaxSettingController@print']);

    Route::post('/store', ['as' => 'tax_setting.store', 'uses' => 'TaxSettingController@store']);
    Route::get('/edit/{id}', ['as' => 'tax_setting.edit', 'uses' => 'TaxSettingController@edit']);
    Route::put('/update/{id}', ['as' => 'tax_setting.update', 'uses' => 'TaxSettingController@update']);
    Route::delete('/delete/{id}', ['as' => 'tax_setting.destroy', 'uses' => 'TaxSettingController@destroy']);
    
});
