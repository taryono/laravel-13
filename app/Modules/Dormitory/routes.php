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
Route::group(['prefix' => 'dormitory', 'namespace' => 'App\Modules\Dormitory\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'dormitory.index', 'uses' => 'DormitoryController@index']);
    Route::any('/search', ['as' => 'dormitory.search', 'uses' => 'DormitoryController@search']);
    Route::get('/create', ['as' => 'dormitory.create', 'uses' => 'DormitoryController@create']);
    Route::get('/show/{id}', ['as' => 'dormitory.show', 'uses' => 'DormitoryController@show']);
    Route::get('/preview/{id}', ['as' => 'dormitory.preview', 'uses' => 'DormitoryController@preview']);
    Route::get('/print/{id}', ['as' => 'dormitory.print', 'uses' => 'DormitoryController@print']);

    Route::post('/store', ['as' => 'dormitory.store', 'uses' => 'DormitoryController@store']);
    Route::get('/edit/{id}', ['as' => 'dormitory.edit', 'uses' => 'DormitoryController@edit']);
    Route::put('/update/{id}', ['as' => 'dormitory.update', 'uses' => 'DormitoryController@update']);
    Route::delete('/delete/{id}', ['as' => 'dormitory.destroy', 'uses' => 'DormitoryController@destroy']);
    
});
