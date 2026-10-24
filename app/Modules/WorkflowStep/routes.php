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
Route::group(['prefix' => 'workflow-step', 'namespace' => 'App\Modules\WorkflowStep\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'workflow_step.index', 'uses' => 'WorkflowStepController@index']);
    Route::any('/search', ['as' => 'workflow_step.search', 'uses' => 'WorkflowStepController@search']);
    Route::get('/create', ['as' => 'workflow_step.create', 'uses' => 'WorkflowStepController@create']);
    Route::get('/show/{id}', ['as' => 'workflow_step.show', 'uses' => 'WorkflowStepController@show']);
    Route::get('/preview/{id}', ['as' => 'workflow_step.preview', 'uses' => 'WorkflowStepController@preview']);
    Route::get('/print/{id}', ['as' => 'workflow_step.print', 'uses' => 'WorkflowStepController@print']);

    Route::post('/store', ['as' => 'workflow_step.store', 'uses' => 'WorkflowStepController@store']);
    Route::get('/edit/{id}', ['as' => 'workflow_step.edit', 'uses' => 'WorkflowStepController@edit']);
    Route::put('/update/{id}', ['as' => 'workflow_step.update', 'uses' => 'WorkflowStepController@update']);
    Route::delete('/delete/{id}', ['as' => 'workflow_step.destroy', 'uses' => 'WorkflowStepController@destroy']);
    
});
