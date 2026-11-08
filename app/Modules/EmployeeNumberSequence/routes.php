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
Route::group(['prefix' => 'employee-number-sequence', 'namespace' => 'App\Modules\EmployeeNumberSequence\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'employee_number_sequence.index', 'uses' => 'EmployeeNumberSequenceController@index']);
    Route::any('/search', ['as' => 'employee_number_sequence.search', 'uses' => 'EmployeeNumberSequenceController@search']);
    Route::get('/create', ['as' => 'employee_number_sequence.create', 'uses' => 'EmployeeNumberSequenceController@create']);
    Route::get('/show/{id}', ['as' => 'employee_number_sequence.show', 'uses' => 'EmployeeNumberSequenceController@show']);
    Route::get('/preview/{id}', ['as' => 'employee_number_sequence.preview', 'uses' => 'EmployeeNumberSequenceController@preview']);
    Route::get('/print/{id}', ['as' => 'employee_number_sequence.print', 'uses' => 'EmployeeNumberSequenceController@print']);

    Route::post('/store', ['as' => 'employee_number_sequence.store', 'uses' => 'EmployeeNumberSequenceController@store']);
    Route::get('/edit/{id}', ['as' => 'employee_number_sequence.edit', 'uses' => 'EmployeeNumberSequenceController@edit']);
    Route::put('/update/{id}', ['as' => 'employee_number_sequence.update', 'uses' => 'EmployeeNumberSequenceController@update']);
    Route::delete('/delete/{id}', ['as' => 'employee_number_sequence.destroy', 'uses' => 'EmployeeNumberSequenceController@destroy']);
    
});
