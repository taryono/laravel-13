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
Route::group(['prefix' => 'admission-quota-setting', 'namespace' => 'App\Modules\AdmissionQuotaSetting\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'admission_quota_setting.index', 'uses' => 'AdmissionQuotaSettingController@index']);
    Route::any('/search', ['as' => 'admission_quota_setting.search', 'uses' => 'AdmissionQuotaSettingController@search']);
    Route::get('/create', ['as' => 'admission_quota_setting.create', 'uses' => 'AdmissionQuotaSettingController@create']);
    Route::get('/show/{id}', ['as' => 'admission_quota_setting.show', 'uses' => 'AdmissionQuotaSettingController@show']);
    Route::get('/preview/{id}', ['as' => 'admission_quota_setting.preview', 'uses' => 'AdmissionQuotaSettingController@preview']);
    Route::get('/print/{id}', ['as' => 'admission_quota_setting.print', 'uses' => 'AdmissionQuotaSettingController@print']);

    Route::post('/store', ['as' => 'admission_quota_setting.store', 'uses' => 'AdmissionQuotaSettingController@store']);
    Route::get('/edit/{id}', ['as' => 'admission_quota_setting.edit', 'uses' => 'AdmissionQuotaSettingController@edit']);
    Route::put('/update/{id}', ['as' => 'admission_quota_setting.update', 'uses' => 'AdmissionQuotaSettingController@update']);
    Route::delete('/delete/{id}', ['as' => 'admission_quota_setting.destroy', 'uses' => 'AdmissionQuotaSettingController@destroy']);
    
});
