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
Route::group(['prefix' => 'parents', 'namespace' => 'App\Modules\Parents\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'parents.index', 'uses' => 'ParentsController@index']);
    Route::any('/search', ['as' => 'parents.search', 'uses' => 'ParentsController@search']);
    Route::get('/create', ['as' => 'parents.create', 'uses' => 'ParentsController@create']);
    Route::get('/show/{id}', ['as' => 'parents.show', 'uses' => 'ParentsController@show']);
    Route::get('/preview/{id}', ['as' => 'parents.preview', 'uses' => 'ParentsController@preview']);
    Route::get('/print/{id}', ['as' => 'parents.print', 'uses' => 'ParentsController@print']);

    Route::post('/store', ['as' => 'parents.store', 'uses' => 'ParentsController@store']);
    Route::get('/edit/{id}', ['as' => 'parents.edit', 'uses' => 'ParentsController@edit']);
    Route::put('/update/{id}', ['as' => 'parents.update', 'uses' => 'ParentsController@update']);
    Route::delete('/delete/{id}', ['as' => 'parents.destroy', 'uses' => 'ParentsController@destroy']);
    
});
