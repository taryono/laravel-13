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
Route::group(['prefix' => 'digital-signature', 'namespace' => 'App\Modules\DigitalSignature\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'digital_signature.index', 'uses' => 'DigitalSignatureController@index']);
    Route::any('/search', ['as' => 'digital_signature.search', 'uses' => 'DigitalSignatureController@search']);
    Route::get('/create', ['as' => 'digital_signature.create', 'uses' => 'DigitalSignatureController@create']);
    Route::get('/show/{id}', ['as' => 'digital_signature.show', 'uses' => 'DigitalSignatureController@show']);
    Route::get('/preview/{id}', ['as' => 'digital_signature.preview', 'uses' => 'DigitalSignatureController@preview']);
    Route::get('/print/{id}', ['as' => 'digital_signature.print', 'uses' => 'DigitalSignatureController@print']);

    Route::post('/store', ['as' => 'digital_signature.store', 'uses' => 'DigitalSignatureController@store']);
    Route::get('/edit/{id}', ['as' => 'digital_signature.edit', 'uses' => 'DigitalSignatureController@edit']);
    Route::put('/update/{id}', ['as' => 'digital_signature.update', 'uses' => 'DigitalSignatureController@update']);
    Route::delete('/delete/{id}', ['as' => 'digital_signature.destroy', 'uses' => 'DigitalSignatureController@destroy']);
    
});
