<?php

use Illuminate\Support\Facades\Route;
use App\Modules\MenuManagement\Controllers\MenuManagementController;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
| , 'superuser'
 */
Route::group(['prefix' => 'menu_management', 'namespace' => 'App\Modules\MenuManagement\Controllers', 'middleware' => ['web', 'auth','superuser']], function () {
    Route::get('/', ['as' => 'menu_management.index', 'uses' => 'MenuManagementController@index']);
    Route::any('/search', ['as' => 'menu_management.search', 'uses' => 'MenuManagementController@search']);
    Route::get('/create', ['as' => 'menu_management.create', 'uses' => 'MenuManagementController@create']);
    Route::get('/show/{id}', ['as' => 'menu_management.show', 'uses' => 'MenuManagementController@show']);
    Route::post('/store', ['as' => 'menu_management.store', 'uses' => 'MenuManagementController@store']);
    Route::get('/edit/{id}', ['as' => 'menu_management.edit', 'uses' => 'MenuManagementController@edit']);
    Route::put('/update/{id}', ['as' => 'menu_management.update', 'uses' => 'MenuManagementController@update']);
    Route::delete('/delete/{id}', ['as' => 'menu_management.destroy', 'uses' => 'MenuManagementController@destroy']); 
});

Route::prefix('admin/menus')->middleware(['auth', 'superuser'])->group(function () {
    Route::get('/', [MenuManagementController::class, 'index'])->name('admin.menus.index');
    Route::post('/', [MenuManagementController::class, 'store'])->name('admin.menus.store');
    Route::get('/{menu}', [MenuManagementController::class, 'show'])->name('admin.menus.show');
    Route::put('/{menu}', [MenuManagementController::class, 'update'])->name('admin.menus.update');
    Route::delete('/{menu}', [MenuManagementController::class, 'destroy'])->name('admin.menus.destroy');
    Route::post('/reorder/save', [MenuManagementController::class, 'reorder'])->name('admin.menus.reorder');
});
