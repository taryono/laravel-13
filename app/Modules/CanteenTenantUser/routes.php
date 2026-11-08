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
Route::group(['prefix' => 'canteen-tenant-user', 'namespace' => 'App\Modules\CanteenTenantUser\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'canteen_tenant_user.index', 'uses' => 'CanteenTenantUserController@index']);
    Route::any('/search', ['as' => 'canteen_tenant_user.search', 'uses' => 'CanteenTenantUserController@search']);
    Route::get('/create', ['as' => 'canteen_tenant_user.create', 'uses' => 'CanteenTenantUserController@create']);
    Route::get('/show/{id}', ['as' => 'canteen_tenant_user.show', 'uses' => 'CanteenTenantUserController@show']);
    Route::get('/preview/{id}', ['as' => 'canteen_tenant_user.preview', 'uses' => 'CanteenTenantUserController@preview']);
    Route::get('/print/{id}', ['as' => 'canteen_tenant_user.print', 'uses' => 'CanteenTenantUserController@print']);

    Route::post('/store', ['as' => 'canteen_tenant_user.store', 'uses' => 'CanteenTenantUserController@store']);
    Route::get('/edit/{id}', ['as' => 'canteen_tenant_user.edit', 'uses' => 'CanteenTenantUserController@edit']);
    Route::put('/update/{id}', ['as' => 'canteen_tenant_user.update', 'uses' => 'CanteenTenantUserController@update']);
    Route::delete('/delete/{id}', ['as' => 'canteen_tenant_user.destroy', 'uses' => 'CanteenTenantUserController@destroy']);
    
});
