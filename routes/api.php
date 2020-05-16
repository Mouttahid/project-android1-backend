<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::group(['middleware' => 'role:Admin'], function () {
            Route::post('create', 'AuthController@createUser');
            Route::get("chiefs","AuthController@getChiefs");
        });
        Route::post('logout', 'AuthController@logout');
        Route::post('user', 'AuthController@user');
        Route::post('userroles','AuthController@checkUserRole');
        Route::post('userwroles','AuthController@UserWithRoles');
        Route::post('verifyauth',"AuthController@verifyAuth");
        Route::post('assign',"AuthController@asignUsertoTeam");
    });
});

Route::group(['prefix' => 'tasks'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get("mine","TasksController@getAuthedUserTasks");
        Route::get("user","TasksController@getTasksPerUser");
        Route::post("create","TasksController@create");
        Route::post("delete","TasksController@delete");
        Route::post("update","TasksController@update");
    });
});