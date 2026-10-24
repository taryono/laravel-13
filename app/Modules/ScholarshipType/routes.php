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
Route::group(['prefix' => 'scholarship-type', 'namespace' => 'App\Modules\ScholarshipType\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'scholarship_type.index', 'uses' => 'ScholarshipTypeController@index']);
    Route::any('/search', ['as' => 'scholarship_type.search', 'uses' => 'ScholarshipTypeController@search']);
    Route::get('/create', ['as' => 'scholarship_type.create', 'uses' => 'ScholarshipTypeController@create']);
    Route::get('/show/{id}', ['as' => 'scholarship_type.show', 'uses' => 'ScholarshipTypeController@show']);
    Route::get('/preview/{id}', ['as' => 'scholarship_type.preview', 'uses' => 'ScholarshipTypeController@preview']);
    Route::get('/print/{id}', ['as' => 'scholarship_type.print', 'uses' => 'ScholarshipTypeController@print']);

    Route::post('/store', ['as' => 'scholarship_type.store', 'uses' => 'ScholarshipTypeController@store']);
    Route::get('/edit/{id}', ['as' => 'scholarship_type.edit', 'uses' => 'ScholarshipTypeController@edit']);
    Route::put('/update/{id}', ['as' => 'scholarship_type.update', 'uses' => 'ScholarshipTypeController@update']);
    Route::delete('/delete/{id}', ['as' => 'scholarship_type.destroy', 'uses' => 'ScholarshipTypeController@destroy']);
    
});
