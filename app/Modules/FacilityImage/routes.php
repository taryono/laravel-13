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
Route::group(['prefix' => 'facility-image', 'namespace' => 'App\Modules\FacilityImage\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'facility_image.index', 'uses' => 'FacilityImageController@index']);
    Route::any('/search', ['as' => 'facility_image.search', 'uses' => 'FacilityImageController@search']);
    Route::get('/create', ['as' => 'facility_image.create', 'uses' => 'FacilityImageController@create']);
    Route::get('/show/{id}', ['as' => 'facility_image.show', 'uses' => 'FacilityImageController@show']);
    Route::get('/preview/{id}', ['as' => 'facility_image.preview', 'uses' => 'FacilityImageController@preview']);
    Route::get('/print/{id}', ['as' => 'facility_image.print', 'uses' => 'FacilityImageController@print']);

    Route::post('/store', ['as' => 'facility_image.store', 'uses' => 'FacilityImageController@store']);
    Route::get('/edit/{id}', ['as' => 'facility_image.edit', 'uses' => 'FacilityImageController@edit']);
    Route::put('/update/{id}', ['as' => 'facility_image.update', 'uses' => 'FacilityImageController@update']);
    Route::delete('/delete/{id}', ['as' => 'facility_image.destroy', 'uses' => 'FacilityImageController@destroy']);
    
});
