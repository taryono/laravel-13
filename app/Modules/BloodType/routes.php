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
Route::group(['prefix' => 'blood-type', 'namespace' => 'App\Modules\BloodType\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'blood_type.index', 'uses' => 'BloodTypeController@index']);
    Route::any('/search', ['as' => 'blood_type.search', 'uses' => 'BloodTypeController@search']);
    Route::get('/create', ['as' => 'blood_type.create', 'uses' => 'BloodTypeController@create']);
    Route::get('/show/{id}', ['as' => 'blood_type.show', 'uses' => 'BloodTypeController@show']);
    Route::get('/preview/{id}', ['as' => 'blood_type.preview', 'uses' => 'BloodTypeController@preview']);
    Route::get('/print/{id}', ['as' => 'blood_type.print', 'uses' => 'BloodTypeController@print']);

    Route::post('/store', ['as' => 'blood_type.store', 'uses' => 'BloodTypeController@store']);
    Route::get('/edit/{id}', ['as' => 'blood_type.edit', 'uses' => 'BloodTypeController@edit']);
    Route::put('/update/{id}', ['as' => 'blood_type.update', 'uses' => 'BloodTypeController@update']);
    Route::delete('/delete/{id}', ['as' => 'blood_type.destroy', 'uses' => 'BloodTypeController@destroy']);
    
});
