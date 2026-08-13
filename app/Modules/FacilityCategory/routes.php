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
Route::group(['prefix' => 'facility-category', 'namespace' => 'App\Modules\FacilityCategory\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'facility_category.index', 'uses' => 'FacilityCategoryController@index']);
    Route::any('/search', ['as' => 'facility_category.search', 'uses' => 'FacilityCategoryController@search']);
    Route::get('/create', ['as' => 'facility_category.create', 'uses' => 'FacilityCategoryController@create']);
    Route::get('/show/{id}', ['as' => 'facility_category.show', 'uses' => 'FacilityCategoryController@show']);
    Route::get('/preview/{id}', ['as' => 'facility_category.preview', 'uses' => 'FacilityCategoryController@preview']);
    Route::get('/print/{id}', ['as' => 'facility_category.print', 'uses' => 'FacilityCategoryController@print']);

    Route::post('/store', ['as' => 'facility_category.store', 'uses' => 'FacilityCategoryController@store']);
    Route::get('/edit/{id}', ['as' => 'facility_category.edit', 'uses' => 'FacilityCategoryController@edit']);
    Route::put('/update/{id}', ['as' => 'facility_category.update', 'uses' => 'FacilityCategoryController@update']);
    Route::delete('/delete/{id}', ['as' => 'facility_category.destroy', 'uses' => 'FacilityCategoryController@destroy']);
    
});
