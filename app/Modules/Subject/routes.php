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

Route::group(['prefix' => 'subject', 'namespace' => 'App\Modules\Subject\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'subject.index', 'uses' => 'SubjectController@index']);
    Route::any('/search', ['as' => 'subject.search', 'uses' => 'SubjectController@search']);
    Route::get('/create', ['as' => 'subject.create', 'uses' => 'SubjectController@create']);
    Route::get('/show/{id}', ['as' => 'subject.show', 'uses' => 'SubjectController@show']);
    Route::get('/preview/{id}', ['as' => 'subject.preview', 'uses' => 'SubjectController@preview']);
    Route::get('/print/{id}', ['as' => 'subject.print', 'uses' => 'SubjectController@print']);

    Route::post('/store', ['as' => 'subject.store', 'uses' => 'SubjectController@store']);
    Route::get('/edit/{id}', ['as' => 'subject.edit', 'uses' => 'SubjectController@edit']);
    Route::put('/update/{id}', ['as' => 'subject.update', 'uses' => 'SubjectController@update']);
    Route::delete('/delete/{id}', ['as' => 'subject.destroy', 'uses' => 'SubjectController@destroy']);
    Route::get('/tree-data', ['as' => 'subject.tree-data', 'uses' => 'SubjectController@treeData']);
});
