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
Route::group(['prefix' => 'dormitory-assignment', 'namespace' => 'App\Modules\DormitoryAssignment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'dormitory_assignment.index', 'uses' => 'DormitoryAssignmentController@index']);
    Route::any('/search', ['as' => 'dormitory_assignment.search', 'uses' => 'DormitoryAssignmentController@search']);
    Route::get('/create', ['as' => 'dormitory_assignment.create', 'uses' => 'DormitoryAssignmentController@create']);
    Route::get('/show/{id}', ['as' => 'dormitory_assignment.show', 'uses' => 'DormitoryAssignmentController@show']);
    Route::get('/preview/{id}', ['as' => 'dormitory_assignment.preview', 'uses' => 'DormitoryAssignmentController@preview']);
    Route::get('/print/{id}', ['as' => 'dormitory_assignment.print', 'uses' => 'DormitoryAssignmentController@print']);

    Route::post('/store', ['as' => 'dormitory_assignment.store', 'uses' => 'DormitoryAssignmentController@store']);
    Route::get('/edit/{id}', ['as' => 'dormitory_assignment.edit', 'uses' => 'DormitoryAssignmentController@edit']);
    Route::put('/update/{id}', ['as' => 'dormitory_assignment.update', 'uses' => 'DormitoryAssignmentController@update']);
    Route::delete('/delete/{id}', ['as' => 'dormitory_assignment.destroy', 'uses' => 'DormitoryAssignmentController@destroy']);
    
});
