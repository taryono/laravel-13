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
Route::group(['prefix' => 'audit-trail', 'namespace' => 'App\Modules\AuditTrail\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'audit_trail.index', 'uses' => 'AuditTrailController@index']);
    Route::any('/search', ['as' => 'audit_trail.search', 'uses' => 'AuditTrailController@search']);
    Route::get('/create', ['as' => 'audit_trail.create', 'uses' => 'AuditTrailController@create']);
    Route::get('/show/{id}', ['as' => 'audit_trail.show', 'uses' => 'AuditTrailController@show']);
    Route::get('/preview/{id}', ['as' => 'audit_trail.preview', 'uses' => 'AuditTrailController@preview']);
    Route::get('/print/{id}', ['as' => 'audit_trail.print', 'uses' => 'AuditTrailController@print']);

    Route::post('/store', ['as' => 'audit_trail.store', 'uses' => 'AuditTrailController@store']);
    Route::get('/edit/{id}', ['as' => 'audit_trail.edit', 'uses' => 'AuditTrailController@edit']);
    Route::put('/update/{id}', ['as' => 'audit_trail.update', 'uses' => 'AuditTrailController@update']);
    Route::delete('/delete/{id}', ['as' => 'audit_trail.destroy', 'uses' => 'AuditTrailController@destroy']);
    
});
