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
Route::group(['prefix' => 'kitab-reference', 'namespace' => 'App\Modules\KitabReference\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'kitab_reference.index', 'uses' => 'KitabReferenceController@index']);
    Route::any('/search', ['as' => 'kitab_reference.search', 'uses' => 'KitabReferenceController@search']);
    Route::get('/create', ['as' => 'kitab_reference.create', 'uses' => 'KitabReferenceController@create']);
    Route::get('/show/{id}', ['as' => 'kitab_reference.show', 'uses' => 'KitabReferenceController@show']);
    Route::get('/preview/{id}', ['as' => 'kitab_reference.preview', 'uses' => 'KitabReferenceController@preview']);
    Route::get('/print/{id}', ['as' => 'kitab_reference.print', 'uses' => 'KitabReferenceController@print']);

    Route::post('/store', ['as' => 'kitab_reference.store', 'uses' => 'KitabReferenceController@store']);
    Route::get('/edit/{id}', ['as' => 'kitab_reference.edit', 'uses' => 'KitabReferenceController@edit']);
    Route::put('/update/{id}', ['as' => 'kitab_reference.update', 'uses' => 'KitabReferenceController@update']);
    Route::delete('/delete/{id}', ['as' => 'kitab_reference.destroy', 'uses' => 'KitabReferenceController@destroy']);
    
});
