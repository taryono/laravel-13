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
    Route::get('/', ['as' => 'crud_generator.index', 'uses' => 'CrudGeneratorController@index']);
    Route::post('/generate', ['as' => 'crud_generator.generate', 'uses' => 'CrudGeneratorController@generate']);
    Route::post('/generate-async', ['as' => 'crud_generator.generateAsync', 'uses' => 'CrudGeneratorController@generateAsync']);
    Route::get('/status/{processId}', ['as' => 'crud_generator.status', 'uses' => 'CrudGeneratorController@status']);
    Route::get('/history', ['as' => 'crud_generator.history', 'uses' => 'CrudGeneratorController@history']); 
    Route::post('/menuGenerator', ['as' => 'crud_generator.menuGenerator', 'uses' => 'CrudGeneratorController@menuGenerator']);
});
