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
Route::group(['prefix' => 'payroll-audit-log', 'namespace' => 'App\Modules\PayrollAuditLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payroll_audit_log.index', 'uses' => 'PayrollAuditLogController@index']);
    Route::any('/search', ['as' => 'payroll_audit_log.search', 'uses' => 'PayrollAuditLogController@search']);
    Route::get('/create', ['as' => 'payroll_audit_log.create', 'uses' => 'PayrollAuditLogController@create']);
    Route::get('/show/{id}', ['as' => 'payroll_audit_log.show', 'uses' => 'PayrollAuditLogController@show']);
    Route::get('/preview/{id}', ['as' => 'payroll_audit_log.preview', 'uses' => 'PayrollAuditLogController@preview']);
    Route::get('/print/{id}', ['as' => 'payroll_audit_log.print', 'uses' => 'PayrollAuditLogController@print']);

    Route::post('/store', ['as' => 'payroll_audit_log.store', 'uses' => 'PayrollAuditLogController@store']);
    Route::get('/edit/{id}', ['as' => 'payroll_audit_log.edit', 'uses' => 'PayrollAuditLogController@edit']);
    Route::put('/update/{id}', ['as' => 'payroll_audit_log.update', 'uses' => 'PayrollAuditLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'payroll_audit_log.destroy', 'uses' => 'PayrollAuditLogController@destroy']);
    
});
