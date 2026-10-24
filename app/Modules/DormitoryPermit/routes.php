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
Route::group(['prefix' => 'dormitory-permit', 'namespace' => 'App\Modules\DormitoryPermit\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'dormitory_permit.index', 'uses' => 'DormitoryPermitController@index']);
    Route::any('/search', ['as' => 'dormitory_permit.search', 'uses' => 'DormitoryPermitController@search']);
    Route::get('/create', ['as' => 'dormitory_permit.create', 'uses' => 'DormitoryPermitController@create']);
    Route::get('/show/{id}', ['as' => 'dormitory_permit.show', 'uses' => 'DormitoryPermitController@show']);
    Route::get('/preview/{id}', ['as' => 'dormitory_permit.preview', 'uses' => 'DormitoryPermitController@preview']);
    Route::get('/print/{id}', ['as' => 'dormitory_permit.print', 'uses' => 'DormitoryPermitController@print']);

    Route::post('/store', ['as' => 'dormitory_permit.store', 'uses' => 'DormitoryPermitController@store']);
    Route::get('/edit/{id}', ['as' => 'dormitory_permit.edit', 'uses' => 'DormitoryPermitController@edit']);
    Route::put('/update/{id}', ['as' => 'dormitory_permit.update', 'uses' => 'DormitoryPermitController@update']);
    Route::delete('/delete/{id}', ['as' => 'dormitory_permit.destroy', 'uses' => 'DormitoryPermitController@destroy']);
    
});
