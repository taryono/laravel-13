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
Route::group(['prefix' => 'workflow-approver', 'namespace' => 'App\Modules\WorkflowApprover\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'workflow_approver.index', 'uses' => 'WorkflowApproverController@index']);
    Route::any('/search', ['as' => 'workflow_approver.search', 'uses' => 'WorkflowApproverController@search']);
    Route::get('/create', ['as' => 'workflow_approver.create', 'uses' => 'WorkflowApproverController@create']);
    Route::get('/show/{id}', ['as' => 'workflow_approver.show', 'uses' => 'WorkflowApproverController@show']);
    Route::get('/preview/{id}', ['as' => 'workflow_approver.preview', 'uses' => 'WorkflowApproverController@preview']);
    Route::get('/print/{id}', ['as' => 'workflow_approver.print', 'uses' => 'WorkflowApproverController@print']);

    Route::post('/store', ['as' => 'workflow_approver.store', 'uses' => 'WorkflowApproverController@store']);
    Route::get('/edit/{id}', ['as' => 'workflow_approver.edit', 'uses' => 'WorkflowApproverController@edit']);
    Route::put('/update/{id}', ['as' => 'workflow_approver.update', 'uses' => 'WorkflowApproverController@update']);
    Route::delete('/delete/{id}', ['as' => 'workflow_approver.destroy', 'uses' => 'WorkflowApproverController@destroy']);
    
});
