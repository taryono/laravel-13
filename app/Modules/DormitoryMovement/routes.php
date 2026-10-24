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
Route::group(['prefix' => 'dormitory-movement', 'namespace' => 'App\Modules\DormitoryMovement\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'dormitory_movement.index', 'uses' => 'DormitoryMovementController@index']);
    Route::any('/search', ['as' => 'dormitory_movement.search', 'uses' => 'DormitoryMovementController@search']);
    Route::get('/create', ['as' => 'dormitory_movement.create', 'uses' => 'DormitoryMovementController@create']);
    Route::get('/show/{id}', ['as' => 'dormitory_movement.show', 'uses' => 'DormitoryMovementController@show']);
    Route::get('/preview/{id}', ['as' => 'dormitory_movement.preview', 'uses' => 'DormitoryMovementController@preview']);
    Route::get('/print/{id}', ['as' => 'dormitory_movement.print', 'uses' => 'DormitoryMovementController@print']);

    Route::post('/store', ['as' => 'dormitory_movement.store', 'uses' => 'DormitoryMovementController@store']);
    Route::get('/edit/{id}', ['as' => 'dormitory_movement.edit', 'uses' => 'DormitoryMovementController@edit']);
    Route::put('/update/{id}', ['as' => 'dormitory_movement.update', 'uses' => 'DormitoryMovementController@update']);
    Route::delete('/delete/{id}', ['as' => 'dormitory_movement.destroy', 'uses' => 'DormitoryMovementController@destroy']);
    
});
