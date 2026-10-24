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
Route::group(['prefix' => 'bos-usage-plan', 'namespace' => 'App\Modules\BosUsagePlan\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'bos_usage_plan.index', 'uses' => 'BosUsagePlanController@index']);
    Route::any('/search', ['as' => 'bos_usage_plan.search', 'uses' => 'BosUsagePlanController@search']);
    Route::get('/create', ['as' => 'bos_usage_plan.create', 'uses' => 'BosUsagePlanController@create']);
    Route::get('/show/{id}', ['as' => 'bos_usage_plan.show', 'uses' => 'BosUsagePlanController@show']);
    Route::get('/preview/{id}', ['as' => 'bos_usage_plan.preview', 'uses' => 'BosUsagePlanController@preview']);
    Route::get('/print/{id}', ['as' => 'bos_usage_plan.print', 'uses' => 'BosUsagePlanController@print']);

    Route::post('/store', ['as' => 'bos_usage_plan.store', 'uses' => 'BosUsagePlanController@store']);
    Route::get('/edit/{id}', ['as' => 'bos_usage_plan.edit', 'uses' => 'BosUsagePlanController@edit']);
    Route::put('/update/{id}', ['as' => 'bos_usage_plan.update', 'uses' => 'BosUsagePlanController@update']);
    Route::delete('/delete/{id}', ['as' => 'bos_usage_plan.destroy', 'uses' => 'BosUsagePlanController@destroy']);
    
});
