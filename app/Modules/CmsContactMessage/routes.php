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
Route::group(['prefix' => 'cms-contact-message', 'namespace' => 'App\Modules\CmsContactMessage\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cms_contact_message.index', 'uses' => 'CmsContactMessageController@index']);
    Route::any('/search', ['as' => 'cms_contact_message.search', 'uses' => 'CmsContactMessageController@search']);
    Route::get('/create', ['as' => 'cms_contact_message.create', 'uses' => 'CmsContactMessageController@create']);
    Route::get('/show/{id}', ['as' => 'cms_contact_message.show', 'uses' => 'CmsContactMessageController@show']);
    Route::get('/preview/{id}', ['as' => 'cms_contact_message.preview', 'uses' => 'CmsContactMessageController@preview']);
    Route::get('/print/{id}', ['as' => 'cms_contact_message.print', 'uses' => 'CmsContactMessageController@print']);

    Route::post('/store', ['as' => 'cms_contact_message.store', 'uses' => 'CmsContactMessageController@store']);
    Route::get('/edit/{id}', ['as' => 'cms_contact_message.edit', 'uses' => 'CmsContactMessageController@edit']);
    Route::put('/update/{id}', ['as' => 'cms_contact_message.update', 'uses' => 'CmsContactMessageController@update']);
    Route::delete('/delete/{id}', ['as' => 'cms_contact_message.destroy', 'uses' => 'CmsContactMessageController@destroy']);
    
});
