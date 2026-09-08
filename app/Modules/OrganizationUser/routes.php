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
Route::group(['prefix' => 'organization-user', 'namespace' => 'App\Modules\OrganizationUser\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'organization_user.index', 'uses' => 'OrganizationUserController@index']);
    Route::any('/search', ['as' => 'organization_user.search', 'uses' => 'OrganizationUserController@search']);
    Route::get('/create', ['as' => 'organization_user.create', 'uses' => 'OrganizationUserController@create']);
    Route::get('/show/{id}', ['as' => 'organization_user.show', 'uses' => 'OrganizationUserController@show']);
    Route::get('/preview/{id}', ['as' => 'organization_user.preview', 'uses' => 'OrganizationUserController@preview']);
    Route::get('/print/{id}', ['as' => 'organization_user.print', 'uses' => 'OrganizationUserController@print']);

    Route::post('/store', ['as' => 'organization_user.store', 'uses' => 'OrganizationUserController@store']);
    Route::get('/edit/{id}', ['as' => 'organization_user.edit', 'uses' => 'OrganizationUserController@edit']);
    Route::put('/update/{id}', ['as' => 'organization_user.update', 'uses' => 'OrganizationUserController@update']);
    Route::delete('/delete/{id}', ['as' => 'organization_user.destroy', 'uses' => 'OrganizationUserController@destroy']);
    
});
