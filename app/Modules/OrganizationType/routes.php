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
Route::group(['prefix' => 'organization-type', 'namespace' => 'App\Modules\OrganizationType\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'organization_type.index', 'uses' => 'OrganizationTypeController@index']);
    Route::any('/search', ['as' => 'organization_type.search', 'uses' => 'OrganizationTypeController@search']);
    Route::get('/create', ['as' => 'organization_type.create', 'uses' => 'OrganizationTypeController@create']);
    Route::get('/show/{id}', ['as' => 'organization_type.show', 'uses' => 'OrganizationTypeController@show']);
    Route::get('/preview/{id}', ['as' => 'organization_type.preview', 'uses' => 'OrganizationTypeController@preview']);
    Route::get('/print/{id}', ['as' => 'organization_type.print', 'uses' => 'OrganizationTypeController@print']);

    Route::post('/store', ['as' => 'organization_type.store', 'uses' => 'OrganizationTypeController@store']);
    Route::get('/edit/{id}', ['as' => 'organization_type.edit', 'uses' => 'OrganizationTypeController@edit']);
    Route::put('/update/{id}', ['as' => 'organization_type.update', 'uses' => 'OrganizationTypeController@update']);
    Route::delete('/delete/{id}', ['as' => 'organization_type.destroy', 'uses' => 'OrganizationTypeController@destroy']);
    
});
