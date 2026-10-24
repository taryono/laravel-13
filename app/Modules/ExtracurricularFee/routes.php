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
Route::group(['prefix' => 'extracurricular-fee', 'namespace' => 'App\Modules\ExtracurricularFee\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'extracurricular_fee.index', 'uses' => 'ExtracurricularFeeController@index']);
    Route::any('/search', ['as' => 'extracurricular_fee.search', 'uses' => 'ExtracurricularFeeController@search']);
    Route::get('/create', ['as' => 'extracurricular_fee.create', 'uses' => 'ExtracurricularFeeController@create']);
    Route::get('/show/{id}', ['as' => 'extracurricular_fee.show', 'uses' => 'ExtracurricularFeeController@show']);
    Route::get('/preview/{id}', ['as' => 'extracurricular_fee.preview', 'uses' => 'ExtracurricularFeeController@preview']);
    Route::get('/print/{id}', ['as' => 'extracurricular_fee.print', 'uses' => 'ExtracurricularFeeController@print']);

    Route::post('/store', ['as' => 'extracurricular_fee.store', 'uses' => 'ExtracurricularFeeController@store']);
    Route::get('/edit/{id}', ['as' => 'extracurricular_fee.edit', 'uses' => 'ExtracurricularFeeController@edit']);
    Route::put('/update/{id}', ['as' => 'extracurricular_fee.update', 'uses' => 'ExtracurricularFeeController@update']);
    Route::delete('/delete/{id}', ['as' => 'extracurricular_fee.destroy', 'uses' => 'ExtracurricularFeeController@destroy']);
    
});
