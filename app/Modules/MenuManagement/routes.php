<?php
// app/Modules/MenuManagement/routes.php
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix'    => 'menu_management',
    'namespace' => 'App\Modules\MenuManagement\Controllers',
    'middleware' => ['web', 'auth', 'superuser'],
    'as'        => 'menu_management.',
], function () {
    // CRUD menu
    Route::get('/', ['as' => 'index', 'uses' => 'MenuManagementController@index']);
    Route::post('/', ['as' => 'store', 'uses' => 'MenuManagementController@store']);
    Route::get('/{menu}', ['as' => 'show', 'uses' => 'MenuManagementController@show']);
    Route::put('/{menu}', ['as' => 'update', 'uses' => 'MenuManagementController@update']);
    Route::delete('/{menu}', ['as' => 'destroy', 'uses' => 'MenuManagementController@destroy']);
    // Drag & drop reorder
    Route::post('/reorder/save', ['as' => 'reorder', 'uses' => 'MenuManagementController@reorder']);
    // Assign role + flag CRUD ke menu (menu_role)
    Route::post('/{menu}/roles', ['as' => 'roles.sync', 'uses' => 'MenuManagementController@syncRoles']);
    // CRUD action per menu
    Route::get('/{menu}/actions', ['as' => 'actions.index', 'uses' => 'MenuManagementController@actionsIndex']);
    Route::post('/{menu}/actions', ['as' => 'actions.store', 'uses' => 'MenuManagementController@storeAction']);
    Route::put('/actions/{action}', ['as' => 'actions.update', 'uses' => 'MenuManagementController@updateAction']);
    Route::delete('/actions/{action}', ['as' => 'actions.destroy', 'uses' => 'MenuManagementController@destroyAction']);
    // Assign action ke role/user (action_role, action_user)
    Route::post('/actions/{action}/access', ['as' => 'actions.syncAccess', 'uses' => 'MenuManagementController@syncActionAccess']);
});
