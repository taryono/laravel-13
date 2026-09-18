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
Route::group(['prefix' => 'student-parent', 'namespace' => 'App\Modules\StudentParent\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_parent.index', 'uses' => 'StudentParentController@index']);
    Route::any('/search', ['as' => 'student_parent.search', 'uses' => 'StudentParentController@search']);
    Route::get('/create', ['as' => 'student_parent.create', 'uses' => 'StudentParentController@create']);
    Route::get('/show/{id}', ['as' => 'student_parent.show', 'uses' => 'StudentParentController@show']);
    Route::get('/preview/{id}', ['as' => 'student_parent.preview', 'uses' => 'StudentParentController@preview']);
    Route::get('/print/{id}', ['as' => 'student_parent.print', 'uses' => 'StudentParentController@print']);

    Route::post('/store', ['as' => 'student_parent.store', 'uses' => 'StudentParentController@store']);
    Route::get('/edit/{id}', ['as' => 'student_parent.edit', 'uses' => 'StudentParentController@edit']);
    Route::put('/update/{id}', ['as' => 'student_parent.update', 'uses' => 'StudentParentController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_parent.destroy', 'uses' => 'StudentParentController@destroy']);
    
});
