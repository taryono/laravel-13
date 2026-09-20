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
Route::group(['prefix' => 'exam-package', 'namespace' => 'App\Modules\ExamPackage\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'exam_package.index', 'uses' => 'ExamPackageController@index']);
    Route::any('/search', ['as' => 'exam_package.search', 'uses' => 'ExamPackageController@search']);
    Route::get('/create', ['as' => 'exam_package.create', 'uses' => 'ExamPackageController@create']);
    Route::get('/show/{id}', ['as' => 'exam_package.show', 'uses' => 'ExamPackageController@show']);
    Route::get('/preview/{id}', ['as' => 'exam_package.preview', 'uses' => 'ExamPackageController@preview']);
    Route::get('/print/{id}', ['as' => 'exam_package.print', 'uses' => 'ExamPackageController@print']);

    Route::post('/store', ['as' => 'exam_package.store', 'uses' => 'ExamPackageController@store']);
    Route::get('/edit/{id}', ['as' => 'exam_package.edit', 'uses' => 'ExamPackageController@edit']);
    Route::put('/update/{id}', ['as' => 'exam_package.update', 'uses' => 'ExamPackageController@update']);
    Route::delete('/delete/{id}', ['as' => 'exam_package.destroy', 'uses' => 'ExamPackageController@destroy']);
    
});
