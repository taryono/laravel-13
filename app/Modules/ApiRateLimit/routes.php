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
Route::group(['prefix' => 'api-rate-limit', 'namespace' => 'App\Modules\ApiRateLimit\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'api_rate_limit.index', 'uses' => 'ApiRateLimitController@index']);
    Route::any('/search', ['as' => 'api_rate_limit.search', 'uses' => 'ApiRateLimitController@search']);
    Route::get('/create', ['as' => 'api_rate_limit.create', 'uses' => 'ApiRateLimitController@create']);
    Route::get('/show/{id}', ['as' => 'api_rate_limit.show', 'uses' => 'ApiRateLimitController@show']);
    Route::get('/preview/{id}', ['as' => 'api_rate_limit.preview', 'uses' => 'ApiRateLimitController@preview']);
    Route::get('/print/{id}', ['as' => 'api_rate_limit.print', 'uses' => 'ApiRateLimitController@print']);

    Route::post('/store', ['as' => 'api_rate_limit.store', 'uses' => 'ApiRateLimitController@store']);
    Route::get('/edit/{id}', ['as' => 'api_rate_limit.edit', 'uses' => 'ApiRateLimitController@edit']);
    Route::put('/update/{id}', ['as' => 'api_rate_limit.update', 'uses' => 'ApiRateLimitController@update']);
    Route::delete('/delete/{id}', ['as' => 'api_rate_limit.destroy', 'uses' => 'ApiRateLimitController@destroy']);
    
});
