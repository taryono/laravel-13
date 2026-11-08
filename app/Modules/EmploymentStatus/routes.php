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
Route::group(['prefix' => 'employment-status', 'namespace' => 'App\Modules\EmploymentStatus\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'employment_status.index', 'uses' => 'EmploymentStatusController@index']);
    Route::any('/search', ['as' => 'employment_status.search', 'uses' => 'EmploymentStatusController@search']);
    Route::get('/create', ['as' => 'employment_status.create', 'uses' => 'EmploymentStatusController@create']);
    Route::get('/show/{id}', ['as' => 'employment_status.show', 'uses' => 'EmploymentStatusController@show']);
    Route::get('/preview/{id}', ['as' => 'employment_status.preview', 'uses' => 'EmploymentStatusController@preview']);
    Route::get('/print/{id}', ['as' => 'employment_status.print', 'uses' => 'EmploymentStatusController@print']);

    Route::post('/store', ['as' => 'employment_status.store', 'uses' => 'EmploymentStatusController@store']);
    Route::get('/edit/{id}', ['as' => 'employment_status.edit', 'uses' => 'EmploymentStatusController@edit']);
    Route::put('/update/{id}', ['as' => 'employment_status.update', 'uses' => 'EmploymentStatusController@update']);
    Route::delete('/delete/{id}', ['as' => 'employment_status.destroy', 'uses' => 'EmploymentStatusController@destroy']);
    
});
