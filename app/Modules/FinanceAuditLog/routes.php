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
Route::group(['prefix' => 'finance-audit-log', 'namespace' => 'App\Modules\FinanceAuditLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'finance_audit_log.index', 'uses' => 'FinanceAuditLogController@index']);
    Route::any('/search', ['as' => 'finance_audit_log.search', 'uses' => 'FinanceAuditLogController@search']);
    Route::get('/create', ['as' => 'finance_audit_log.create', 'uses' => 'FinanceAuditLogController@create']);
    Route::get('/show/{id}', ['as' => 'finance_audit_log.show', 'uses' => 'FinanceAuditLogController@show']);
    Route::get('/preview/{id}', ['as' => 'finance_audit_log.preview', 'uses' => 'FinanceAuditLogController@preview']);
    Route::get('/print/{id}', ['as' => 'finance_audit_log.print', 'uses' => 'FinanceAuditLogController@print']);

    Route::post('/store', ['as' => 'finance_audit_log.store', 'uses' => 'FinanceAuditLogController@store']);
    Route::get('/edit/{id}', ['as' => 'finance_audit_log.edit', 'uses' => 'FinanceAuditLogController@edit']);
    Route::put('/update/{id}', ['as' => 'finance_audit_log.update', 'uses' => 'FinanceAuditLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'finance_audit_log.destroy', 'uses' => 'FinanceAuditLogController@destroy']);
    
});
