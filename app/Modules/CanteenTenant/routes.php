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
Route::group(['prefix' => 'canteen-tenant', 'namespace' => 'App\Modules\CanteenTenant\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'canteen_tenant.index', 'uses' => 'CanteenTenantController@index']);
    Route::any('/search', ['as' => 'canteen_tenant.search', 'uses' => 'CanteenTenantController@search']);
    Route::get('/create', ['as' => 'canteen_tenant.create', 'uses' => 'CanteenTenantController@create']);
    Route::get('/show/{id}', ['as' => 'canteen_tenant.show', 'uses' => 'CanteenTenantController@show']);
    Route::get('/preview/{id}', ['as' => 'canteen_tenant.preview', 'uses' => 'CanteenTenantController@preview']);
    Route::get('/print/{id}', ['as' => 'canteen_tenant.print', 'uses' => 'CanteenTenantController@print']);

    Route::post('/store', ['as' => 'canteen_tenant.store', 'uses' => 'CanteenTenantController@store']);
    Route::get('/edit/{id}', ['as' => 'canteen_tenant.edit', 'uses' => 'CanteenTenantController@edit']);
    Route::put('/update/{id}', ['as' => 'canteen_tenant.update', 'uses' => 'CanteenTenantController@update']);
    Route::delete('/delete/{id}', ['as' => 'canteen_tenant.destroy', 'uses' => 'CanteenTenantController@destroy']);
    
});
