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
Route::group(['prefix' => 'bonus', 'namespace' => 'App\Modules\Bonus\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'bonus.index', 'uses' => 'BonusController@index']);
    Route::any('/search', ['as' => 'bonus.search', 'uses' => 'BonusController@search']);
    Route::get('/create', ['as' => 'bonus.create', 'uses' => 'BonusController@create']);
    Route::get('/show/{id}', ['as' => 'bonus.show', 'uses' => 'BonusController@show']);
    Route::get('/preview/{id}', ['as' => 'bonus.preview', 'uses' => 'BonusController@preview']);
    Route::get('/print/{id}', ['as' => 'bonus.print', 'uses' => 'BonusController@print']);

    Route::post('/store', ['as' => 'bonus.store', 'uses' => 'BonusController@store']);
    Route::get('/edit/{id}', ['as' => 'bonus.edit', 'uses' => 'BonusController@edit']);
    Route::put('/update/{id}', ['as' => 'bonus.update', 'uses' => 'BonusController@update']);
    Route::delete('/delete/{id}', ['as' => 'bonus.destroy', 'uses' => 'BonusController@destroy']);
    
});
