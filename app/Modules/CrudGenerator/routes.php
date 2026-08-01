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
Route::group(['prefix' => 'crud-generator', 'namespace' => 'App\Modules\CrudGenerator\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'crud-generator.index', 'uses' => 'CrudGeneratorController@index']);
    Route::post('/generate', ['as' => 'crud-generator.generate', 'uses' => 'CrudGeneratorController@index']);
    Route::get('/generate-async', ['as' => 'crud-generator.generateAsync', 'uses' => 'CrudGeneratorController@generateAsync']);
    Route::get('/status/{processId}', ['as' => 'crud-generator.status', 'uses' => 'CrudGeneratorController@status']);
    Route::get('/history', ['as' => 'crud-generator.history', 'uses' => 'CrudGeneratorController@history']); 
});
