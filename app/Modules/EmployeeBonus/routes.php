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
Route::group(['prefix' => 'employee-bonus', 'namespace' => 'App\Modules\EmployeeBonus\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'employee_bonus.index', 'uses' => 'EmployeeBonusController@index']);
    Route::any('/search', ['as' => 'employee_bonus.search', 'uses' => 'EmployeeBonusController@search']);
    Route::get('/create', ['as' => 'employee_bonus.create', 'uses' => 'EmployeeBonusController@create']);
    Route::get('/show/{id}', ['as' => 'employee_bonus.show', 'uses' => 'EmployeeBonusController@show']);
    Route::get('/preview/{id}', ['as' => 'employee_bonus.preview', 'uses' => 'EmployeeBonusController@preview']);
    Route::get('/print/{id}', ['as' => 'employee_bonus.print', 'uses' => 'EmployeeBonusController@print']);

    Route::post('/store', ['as' => 'employee_bonus.store', 'uses' => 'EmployeeBonusController@store']);
    Route::get('/edit/{id}', ['as' => 'employee_bonus.edit', 'uses' => 'EmployeeBonusController@edit']);
    Route::put('/update/{id}', ['as' => 'employee_bonus.update', 'uses' => 'EmployeeBonusController@update']);
    Route::delete('/delete/{id}', ['as' => 'employee_bonus.destroy', 'uses' => 'EmployeeBonusController@destroy']);
    
});
