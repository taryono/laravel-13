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
Route::group(['prefix' => 'workflow-definition', 'namespace' => 'App\Modules\WorkflowDefinition\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'workflow_definition.index', 'uses' => 'WorkflowDefinitionController@index']);
    Route::any('/search', ['as' => 'workflow_definition.search', 'uses' => 'WorkflowDefinitionController@search']);
    Route::get('/create', ['as' => 'workflow_definition.create', 'uses' => 'WorkflowDefinitionController@create']);
    Route::get('/show/{id}', ['as' => 'workflow_definition.show', 'uses' => 'WorkflowDefinitionController@show']);
    Route::get('/preview/{id}', ['as' => 'workflow_definition.preview', 'uses' => 'WorkflowDefinitionController@preview']);
    Route::get('/print/{id}', ['as' => 'workflow_definition.print', 'uses' => 'WorkflowDefinitionController@print']);

    Route::post('/store', ['as' => 'workflow_definition.store', 'uses' => 'WorkflowDefinitionController@store']);
    Route::get('/edit/{id}', ['as' => 'workflow_definition.edit', 'uses' => 'WorkflowDefinitionController@edit']);
    Route::put('/update/{id}', ['as' => 'workflow_definition.update', 'uses' => 'WorkflowDefinitionController@update']);
    Route::delete('/delete/{id}', ['as' => 'workflow_definition.destroy', 'uses' => 'WorkflowDefinitionController@destroy']);
    
});
