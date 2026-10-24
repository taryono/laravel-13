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
Route::group(['prefix' => 'yudisium', 'namespace' => 'App\Modules\Yudisium\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'yudisium.index', 'uses' => 'YudisiumController@index']);
    Route::any('/search', ['as' => 'yudisium.search', 'uses' => 'YudisiumController@search']);
    Route::get('/create', ['as' => 'yudisium.create', 'uses' => 'YudisiumController@create']);
    Route::get('/show/{id}', ['as' => 'yudisium.show', 'uses' => 'YudisiumController@show']);
    Route::get('/preview/{id}', ['as' => 'yudisium.preview', 'uses' => 'YudisiumController@preview']);
    Route::get('/print/{id}', ['as' => 'yudisium.print', 'uses' => 'YudisiumController@print']);

    Route::post('/store', ['as' => 'yudisium.store', 'uses' => 'YudisiumController@store']);
    Route::get('/edit/{id}', ['as' => 'yudisium.edit', 'uses' => 'YudisiumController@edit']);
    Route::put('/update/{id}', ['as' => 'yudisium.update', 'uses' => 'YudisiumController@update']);
    Route::delete('/delete/{id}', ['as' => 'yudisium.destroy', 'uses' => 'YudisiumController@destroy']);
    
});
