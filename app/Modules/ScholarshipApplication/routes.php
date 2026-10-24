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
Route::group(['prefix' => 'scholarship-application', 'namespace' => 'App\Modules\ScholarshipApplication\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'scholarship_application.index', 'uses' => 'ScholarshipApplicationController@index']);
    Route::any('/search', ['as' => 'scholarship_application.search', 'uses' => 'ScholarshipApplicationController@search']);
    Route::get('/create', ['as' => 'scholarship_application.create', 'uses' => 'ScholarshipApplicationController@create']);
    Route::get('/show/{id}', ['as' => 'scholarship_application.show', 'uses' => 'ScholarshipApplicationController@show']);
    Route::get('/preview/{id}', ['as' => 'scholarship_application.preview', 'uses' => 'ScholarshipApplicationController@preview']);
    Route::get('/print/{id}', ['as' => 'scholarship_application.print', 'uses' => 'ScholarshipApplicationController@print']);

    Route::post('/store', ['as' => 'scholarship_application.store', 'uses' => 'ScholarshipApplicationController@store']);
    Route::get('/edit/{id}', ['as' => 'scholarship_application.edit', 'uses' => 'ScholarshipApplicationController@edit']);
    Route::put('/update/{id}', ['as' => 'scholarship_application.update', 'uses' => 'ScholarshipApplicationController@update']);
    Route::delete('/delete/{id}', ['as' => 'scholarship_application.destroy', 'uses' => 'ScholarshipApplicationController@destroy']);
    
});
