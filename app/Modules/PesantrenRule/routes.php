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
Route::group(['prefix' => 'pesantren-rule', 'namespace' => 'App\Modules\PesantrenRule\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'pesantren_rule.index', 'uses' => 'PesantrenRuleController@index']);
    Route::any('/search', ['as' => 'pesantren_rule.search', 'uses' => 'PesantrenRuleController@search']);
    Route::get('/create', ['as' => 'pesantren_rule.create', 'uses' => 'PesantrenRuleController@create']);
    Route::get('/show/{id}', ['as' => 'pesantren_rule.show', 'uses' => 'PesantrenRuleController@show']);
    Route::get('/preview/{id}', ['as' => 'pesantren_rule.preview', 'uses' => 'PesantrenRuleController@preview']);
    Route::get('/print/{id}', ['as' => 'pesantren_rule.print', 'uses' => 'PesantrenRuleController@print']);

    Route::post('/store', ['as' => 'pesantren_rule.store', 'uses' => 'PesantrenRuleController@store']);
    Route::get('/edit/{id}', ['as' => 'pesantren_rule.edit', 'uses' => 'PesantrenRuleController@edit']);
    Route::put('/update/{id}', ['as' => 'pesantren_rule.update', 'uses' => 'PesantrenRuleController@update']);
    Route::delete('/delete/{id}', ['as' => 'pesantren_rule.destroy', 'uses' => 'PesantrenRuleController@destroy']);
    
});
