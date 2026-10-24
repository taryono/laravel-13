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
Route::group(['prefix' => 'bos-period', 'namespace' => 'App\Modules\BosPeriod\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'bos_period.index', 'uses' => 'BosPeriodController@index']);
    Route::any('/search', ['as' => 'bos_period.search', 'uses' => 'BosPeriodController@search']);
    Route::get('/create', ['as' => 'bos_period.create', 'uses' => 'BosPeriodController@create']);
    Route::get('/show/{id}', ['as' => 'bos_period.show', 'uses' => 'BosPeriodController@show']);
    Route::get('/preview/{id}', ['as' => 'bos_period.preview', 'uses' => 'BosPeriodController@preview']);
    Route::get('/print/{id}', ['as' => 'bos_period.print', 'uses' => 'BosPeriodController@print']);

    Route::post('/store', ['as' => 'bos_period.store', 'uses' => 'BosPeriodController@store']);
    Route::get('/edit/{id}', ['as' => 'bos_period.edit', 'uses' => 'BosPeriodController@edit']);
    Route::put('/update/{id}', ['as' => 'bos_period.update', 'uses' => 'BosPeriodController@update']);
    Route::delete('/delete/{id}', ['as' => 'bos_period.destroy', 'uses' => 'BosPeriodController@destroy']);
    
});
