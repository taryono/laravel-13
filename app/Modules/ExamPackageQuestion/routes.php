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
Route::group(['prefix' => 'exam-package-question', 'namespace' => 'App\Modules\ExamPackageQuestion\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'exam_package_question.index', 'uses' => 'ExamPackageQuestionController@index']);
    Route::any('/search', ['as' => 'exam_package_question.search', 'uses' => 'ExamPackageQuestionController@search']);
    Route::get('/create', ['as' => 'exam_package_question.create', 'uses' => 'ExamPackageQuestionController@create']);
    Route::get('/show/{id}', ['as' => 'exam_package_question.show', 'uses' => 'ExamPackageQuestionController@show']);
    Route::get('/preview/{id}', ['as' => 'exam_package_question.preview', 'uses' => 'ExamPackageQuestionController@preview']);
    Route::get('/print/{id}', ['as' => 'exam_package_question.print', 'uses' => 'ExamPackageQuestionController@print']);

    Route::post('/store', ['as' => 'exam_package_question.store', 'uses' => 'ExamPackageQuestionController@store']);
    Route::get('/edit/{id}', ['as' => 'exam_package_question.edit', 'uses' => 'ExamPackageQuestionController@edit']);
    Route::put('/update/{id}', ['as' => 'exam_package_question.update', 'uses' => 'ExamPackageQuestionController@update']);
    Route::delete('/delete/{id}', ['as' => 'exam_package_question.destroy', 'uses' => 'ExamPackageQuestionController@destroy']);
    
});
