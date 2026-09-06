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
Route::group(['prefix' => 'organization', 'namespace' => 'App\Modules\Organization\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'organization.index', 'uses' => 'OrganizationController@index']);
    Route::any('/search', ['as' => 'organization.search', 'uses' => 'OrganizationController@search']);
    Route::get('/create', ['as' => 'organization.create', 'uses' => 'OrganizationController@create']);
    Route::get('/show/{id}', ['as' => 'organization.show', 'uses' => 'OrganizationController@show']);
    Route::get('/preview/{id}', ['as' => 'organization.preview', 'uses' => 'OrganizationController@preview']);
    Route::get('/print/{id}', ['as' => 'organization.print', 'uses' => 'OrganizationController@print']);

    Route::post('/store', ['as' => 'organization.store', 'uses' => 'OrganizationController@store']);
    Route::get('/edit/{id}', ['as' => 'organization.edit', 'uses' => 'OrganizationController@edit']);
    Route::put('/update/{id}', ['as' => 'organization.update', 'uses' => 'OrganizationController@update']);
    Route::delete('/delete/{id}', ['as' => 'organization.destroy', 'uses' => 'OrganizationController@destroy']);
    
});
