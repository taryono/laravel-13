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
Route::group(['prefix' => 'pesantren-activity', 'namespace' => 'App\Modules\PesantrenActivity\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'pesantren_activity.index', 'uses' => 'PesantrenActivityController@index']);
    Route::any('/search', ['as' => 'pesantren_activity.search', 'uses' => 'PesantrenActivityController@search']);
    Route::get('/create', ['as' => 'pesantren_activity.create', 'uses' => 'PesantrenActivityController@create']);
    Route::get('/show/{id}', ['as' => 'pesantren_activity.show', 'uses' => 'PesantrenActivityController@show']);
    Route::get('/preview/{id}', ['as' => 'pesantren_activity.preview', 'uses' => 'PesantrenActivityController@preview']);
    Route::get('/print/{id}', ['as' => 'pesantren_activity.print', 'uses' => 'PesantrenActivityController@print']);

    Route::post('/store', ['as' => 'pesantren_activity.store', 'uses' => 'PesantrenActivityController@store']);
    Route::get('/edit/{id}', ['as' => 'pesantren_activity.edit', 'uses' => 'PesantrenActivityController@edit']);
    Route::put('/update/{id}', ['as' => 'pesantren_activity.update', 'uses' => 'PesantrenActivityController@update']);
    Route::delete('/delete/{id}', ['as' => 'pesantren_activity.destroy', 'uses' => 'PesantrenActivityController@destroy']);
    
});
