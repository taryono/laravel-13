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
Route::group(['prefix' => 'period', 'namespace' => 'App\Modules\Period\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'period.index', 'uses' => 'PeriodController@index']);
    Route::any('/search', ['as' => 'period.search', 'uses' => 'PeriodController@search']);
    Route::get('/create', ['as' => 'period.create', 'uses' => 'PeriodController@create']);
    Route::get('/show/{id}', ['as' => 'period.show', 'uses' => 'PeriodController@show']);
    Route::get('/preview/{id}', ['as' => 'period.preview', 'uses' => 'PeriodController@preview']);
    Route::get('/print/{id}', ['as' => 'period.print', 'uses' => 'PeriodController@print']);

    Route::post('/store', ['as' => 'period.store', 'uses' => 'PeriodController@store']);
    Route::get('/edit/{id}', ['as' => 'period.edit', 'uses' => 'PeriodController@edit']);
    Route::put('/update/{id}', ['as' => 'period.update', 'uses' => 'PeriodController@update']);
    Route::delete('/delete/{id}', ['as' => 'period.destroy', 'uses' => 'PeriodController@destroy']);
    
});
