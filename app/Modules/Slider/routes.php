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
Route::group(['prefix' => 'slider', 'namespace' => 'App\Modules\Slider\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'slider.index', 'uses' => 'SliderController@index']);
    Route::any('/search', ['as' => 'slider.search', 'uses' => 'SliderController@search']);
    Route::get('/create', ['as' => 'slider.create', 'uses' => 'SliderController@create']);
    Route::get('/show/{id}', ['as' => 'slider.show', 'uses' => 'SliderController@show']);
    Route::get('/preview/{id}', ['as' => 'slider.preview', 'uses' => 'SliderController@preview']);
    Route::get('/print/{id}', ['as' => 'slider.print', 'uses' => 'SliderController@print']);

    Route::post('/store', ['as' => 'slider.store', 'uses' => 'SliderController@store']);
    Route::get('/edit/{id}', ['as' => 'slider.edit', 'uses' => 'SliderController@edit']);
    Route::put('/update/{id}', ['as' => 'slider.update', 'uses' => 'SliderController@update']);
    Route::delete('/delete/{id}', ['as' => 'slider.destroy', 'uses' => 'SliderController@destroy']);
    
});
