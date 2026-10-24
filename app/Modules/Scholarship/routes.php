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
Route::group(['prefix' => 'scholarship', 'namespace' => 'App\Modules\Scholarship\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'scholarship.index', 'uses' => 'ScholarshipController@index']);
    Route::any('/search', ['as' => 'scholarship.search', 'uses' => 'ScholarshipController@search']);
    Route::get('/create', ['as' => 'scholarship.create', 'uses' => 'ScholarshipController@create']);
    Route::get('/show/{id}', ['as' => 'scholarship.show', 'uses' => 'ScholarshipController@show']);
    Route::get('/preview/{id}', ['as' => 'scholarship.preview', 'uses' => 'ScholarshipController@preview']);
    Route::get('/print/{id}', ['as' => 'scholarship.print', 'uses' => 'ScholarshipController@print']);

    Route::post('/store', ['as' => 'scholarship.store', 'uses' => 'ScholarshipController@store']);
    Route::get('/edit/{id}', ['as' => 'scholarship.edit', 'uses' => 'ScholarshipController@edit']);
    Route::put('/update/{id}', ['as' => 'scholarship.update', 'uses' => 'ScholarshipController@update']);
    Route::delete('/delete/{id}', ['as' => 'scholarship.destroy', 'uses' => 'ScholarshipController@destroy']);
    
});
