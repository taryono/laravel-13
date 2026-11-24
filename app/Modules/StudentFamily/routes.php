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
Route::group(['prefix' => 'student-family', 'namespace' => 'App\Modules\StudentFamily\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_family.index', 'uses' => 'StudentFamilyController@index']);
    Route::any('/search', ['as' => 'student_family.search', 'uses' => 'StudentFamilyController@search']);
    Route::get('/create', ['as' => 'student_family.create', 'uses' => 'StudentFamilyController@create']);
    Route::get('/show/{id}', ['as' => 'student_family.show', 'uses' => 'StudentFamilyController@show']);
    Route::get('/preview/{id}', ['as' => 'student_family.preview', 'uses' => 'StudentFamilyController@preview']);
    Route::get('/print/{id}', ['as' => 'student_family.print', 'uses' => 'StudentFamilyController@print']);

    Route::post('/store', ['as' => 'student_family.store', 'uses' => 'StudentFamilyController@store']);
    Route::get('/edit/{id}', ['as' => 'student_family.edit', 'uses' => 'StudentFamilyController@edit']);
    Route::put('/update/{id}', ['as' => 'student_family.update', 'uses' => 'StudentFamilyController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_family.destroy', 'uses' => 'StudentFamilyController@destroy']);
    
});
