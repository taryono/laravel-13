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
Route::group(['prefix' => 'policy', 'namespace' => 'App\Modules\Policy\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'policy.index', 'uses' => 'PolicyController@index']);
    Route::any('/search', ['as' => 'policy.search', 'uses' => 'PolicyController@search']);
    Route::get('/create', ['as' => 'policy.create', 'uses' => 'PolicyController@create']);
    Route::get('/show/{policy}', ['as' => 'policy.show', 'uses' => 'PolicyController@show']);
    Route::get('/preview/{policy}', ['as' => 'policy.preview', 'uses' => 'PolicyController@preview']);
    Route::get('/print/{policy}', ['as' => 'policy.print', 'uses' => 'PolicyController@print']);

    Route::post('/store', ['as' => 'policy.store', 'uses' => 'PolicyController@store']);
    Route::get('/edit/{policy}', ['as' => 'policy.edit', 'uses' => 'PolicyController@edit']);
    Route::put('/update/{policy}', ['as' => 'policy.update', 'uses' => 'PolicyController@update']);
    Route::delete('/delete/{policy}', ['as' => 'policy.destroy', 'uses' => 'PolicyController@destroy']);
    
});
