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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register')->name('register');
    Route::post('login', 'AuthController@login')->name('login');
    Route::get('refresh', 'AuthController@refresh')->name('refresh');
    Route::get('user', 'AuthController@user')->name('user');
    Route::post('reset', 'AuthController@reset')->name('forgot-password');




    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'AuthController@logout')->name('logout');


        Route::get('members', 'UserController@members')->name('members');
        Route::post('members/edit', 'UserController@membersEdit')->name('members.edit');
        Route::post('members/delete', 'UserController@membersDelete')->name('members.delete');

        Route::get('clients', 'UserController@clients')->name('clients');
        Route::post('clients/edit', 'UserController@clientsEdit')->name('clients.edit');
        Route::post('clients/delete', 'UserController@clientsDelete')->name('clients.delete');


        Route::get('projects', 'ProjectController@index')->name('projects');
        Route::post('projects/create', 'ProjectController@create')->name('projects.create');
        Route::post('projects/edit', 'ProjectController@edit')->name('projects.edit');
        Route::post('projects/state', 'ProjectController@state')->name('projects.state');
        Route::post('projects/delete', 'ProjectController@delete')->name('projects.delete');

    });
});

Route::group(['middleware' => 'auth:api'], function(){
    // Users
    Route::get('users/{id}', 'UserController@show')->middleware('isAdminOrSelf');
});