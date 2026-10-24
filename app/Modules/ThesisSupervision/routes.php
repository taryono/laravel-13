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
Route::group(['prefix' => 'thesis-supervision', 'namespace' => 'App\Modules\ThesisSupervision\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'thesis_supervision.index', 'uses' => 'ThesisSupervisionController@index']);
    Route::any('/search', ['as' => 'thesis_supervision.search', 'uses' => 'ThesisSupervisionController@search']);
    Route::get('/create', ['as' => 'thesis_supervision.create', 'uses' => 'ThesisSupervisionController@create']);
    Route::get('/show/{id}', ['as' => 'thesis_supervision.show', 'uses' => 'ThesisSupervisionController@show']);
    Route::get('/preview/{id}', ['as' => 'thesis_supervision.preview', 'uses' => 'ThesisSupervisionController@preview']);
    Route::get('/print/{id}', ['as' => 'thesis_supervision.print', 'uses' => 'ThesisSupervisionController@print']);

    Route::post('/store', ['as' => 'thesis_supervision.store', 'uses' => 'ThesisSupervisionController@store']);
    Route::get('/edit/{id}', ['as' => 'thesis_supervision.edit', 'uses' => 'ThesisSupervisionController@edit']);
    Route::put('/update/{id}', ['as' => 'thesis_supervision.update', 'uses' => 'ThesisSupervisionController@update']);
    Route::delete('/delete/{id}', ['as' => 'thesis_supervision.destroy', 'uses' => 'ThesisSupervisionController@destroy']);
    
});
