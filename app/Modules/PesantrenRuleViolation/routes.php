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
Route::group(['prefix' => 'pesantren-rule-violation', 'namespace' => 'App\Modules\PesantrenRuleViolation\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'pesantren_rule_violation.index', 'uses' => 'PesantrenRuleViolationController@index']);
    Route::any('/search', ['as' => 'pesantren_rule_violation.search', 'uses' => 'PesantrenRuleViolationController@search']);
    Route::get('/create', ['as' => 'pesantren_rule_violation.create', 'uses' => 'PesantrenRuleViolationController@create']);
    Route::get('/show/{id}', ['as' => 'pesantren_rule_violation.show', 'uses' => 'PesantrenRuleViolationController@show']);
    Route::get('/preview/{id}', ['as' => 'pesantren_rule_violation.preview', 'uses' => 'PesantrenRuleViolationController@preview']);
    Route::get('/print/{id}', ['as' => 'pesantren_rule_violation.print', 'uses' => 'PesantrenRuleViolationController@print']);

    Route::post('/store', ['as' => 'pesantren_rule_violation.store', 'uses' => 'PesantrenRuleViolationController@store']);
    Route::get('/edit/{id}', ['as' => 'pesantren_rule_violation.edit', 'uses' => 'PesantrenRuleViolationController@edit']);
    Route::put('/update/{id}', ['as' => 'pesantren_rule_violation.update', 'uses' => 'PesantrenRuleViolationController@update']);
    Route::delete('/delete/{id}', ['as' => 'pesantren_rule_violation.destroy', 'uses' => 'PesantrenRuleViolationController@destroy']);
    
});
