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
Route::group(['prefix' => 'holiday', 'namespace' => 'App\Modules\Holiday\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'holiday.index', 'uses' => 'HolidayController@index']);
    Route::any('/search', ['as' => 'holiday.search', 'uses' => 'HolidayController@search']);
    Route::get('/create', ['as' => 'holiday.create', 'uses' => 'HolidayController@create']);
    Route::get('/show/{id}', ['as' => 'holiday.show', 'uses' => 'HolidayController@show']);
    Route::get('/preview/{id}', ['as' => 'holiday.preview', 'uses' => 'HolidayController@preview']);
    Route::get('/print/{id}', ['as' => 'holiday.print', 'uses' => 'HolidayController@print']);

    Route::post('/store', ['as' => 'holiday.store', 'uses' => 'HolidayController@store']);
    Route::get('/edit/{id}', ['as' => 'holiday.edit', 'uses' => 'HolidayController@edit']);
    Route::put('/update/{id}', ['as' => 'holiday.update', 'uses' => 'HolidayController@update']);
    Route::delete('/delete/{id}', ['as' => 'holiday.destroy', 'uses' => 'HolidayController@destroy']);
    
});
