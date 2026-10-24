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
Route::group(['prefix' => 'bos-receipt', 'namespace' => 'App\Modules\BosReceipt\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'bos_receipt.index', 'uses' => 'BosReceiptController@index']);
    Route::any('/search', ['as' => 'bos_receipt.search', 'uses' => 'BosReceiptController@search']);
    Route::get('/create', ['as' => 'bos_receipt.create', 'uses' => 'BosReceiptController@create']);
    Route::get('/show/{id}', ['as' => 'bos_receipt.show', 'uses' => 'BosReceiptController@show']);
    Route::get('/preview/{id}', ['as' => 'bos_receipt.preview', 'uses' => 'BosReceiptController@preview']);
    Route::get('/print/{id}', ['as' => 'bos_receipt.print', 'uses' => 'BosReceiptController@print']);

    Route::post('/store', ['as' => 'bos_receipt.store', 'uses' => 'BosReceiptController@store']);
    Route::get('/edit/{id}', ['as' => 'bos_receipt.edit', 'uses' => 'BosReceiptController@edit']);
    Route::put('/update/{id}', ['as' => 'bos_receipt.update', 'uses' => 'BosReceiptController@update']);
    Route::delete('/delete/{id}', ['as' => 'bos_receipt.destroy', 'uses' => 'BosReceiptController@destroy']);
    
});
