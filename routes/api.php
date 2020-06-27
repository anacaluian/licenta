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


//Route::group(['middleware' => 'auth:api'], function(){
//    Route::get('users/{id}', 'UserController@show')->middleware('isAdminOrSelf');
//});

Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register')->name('register');
    Route::post('login', 'AuthController@login')->name('login');
    Route::get('refresh', 'AuthController@refresh')->name('refresh');
    Route::get('user', 'AuthController@user')->name('user');
    Route::post('reset', 'AuthController@reset')->name('forgot-password');


    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('users/{id}', 'UserController@show')->middleware('isAdminOrSelf');
        Route::post('logout', 'AuthController@logout')->name('logout');
        Route::post('profile/photo', 'UserController@profilePhoto')->name('profile.photo');
        Route::post('user/update', 'UserController@update')->name('user.update');
        Route::post('user/password', 'UserController@changePassword')->name('user.password');


        Route::get('admin', 'UserController@admin')->name('admin');
        Route::post('admin/change', 'UserController@changeRole')->name('admin.change.role');
        Route::get('members', 'UserController@members')->name('members');
        Route::post('members/edit', 'UserController@membersEdit')->name('members.edit');
        Route::post('members/delete', 'UserController@membersDelete')->name('members.delete');

        Route::get('clients', 'UserController@clients')->name('clients');
        Route::post('clients/edit', 'UserController@clientsEdit')->name('clients.edit');
        Route::post('clients/delete', 'UserController@clientsDelete')->name('clients.delete');


        Route::get('projects', 'ProjectController@index')->name('projects');
        Route::post('project/data', 'ProjectController@project')->name('projects.data');
        Route::post('projects/create', 'ProjectController@create')->name('projects.create');
        Route::post('projects/edit', 'ProjectController@edit')->name('projects.edit');
        Route::post('projects/state', 'ProjectController@state')->name('projects.state');
        Route::post('projects/delete', 'ProjectController@delete')->name('projects.delete');
        Route::post('projects/members', 'ProjectController@members')->name('projects.members');


        Route::post('tasks', 'TaskController@index')->name('tasks');
        Route::post('tasks/create', 'TaskController@create')->name('tasks.create');
        Route::post('tasks/update', 'TaskController@update')->name('tasks.update');
        Route::post('tasks/update/task', 'TaskController@updateTask')->name('tasks.update.task');
        Route::post('tasks/delete', 'TaskController@delete')->name('tasks.delete');


        Route::get('comments/{project}/{task}', 'CommentController@index')->name('comments');
        Route::post('comments/create', 'CommentController@create')->name('comments.create');
        Route::post('comments/upload/{task}', 'CommentController@upload')->name('comments.upload');
        Route::post('comments/delete', 'CommentController@delete')->name('comments.delete');

        Route::get('notes/{project}', 'NoteController@index')->name('notes');
        Route::post('notes/create', 'NoteController@create')->name('notes.create');
        Route::post('notes/update', 'NoteController@update')->name('notes.update');
        Route::post('notes/delete', 'NoteController@delete')->name('notes.delete');

        Route::get('files/{project}', 'FileController@index')->name('files');
        Route::get('files/download/{file}', 'FileController@download')->name('files.download');
        Route::post('files/create', 'FileController@create')->name('files.create');

        Route::get('times/{project}/{member}', 'TimeController@index')->name('times');
        Route::post('times/create', 'TimeController@create')->name('times.create');
        Route::get('times/month', 'TimeController@monthlyTime')->name('times.month');
        Route::post('times/delete', 'TimeController@delete')->name('times.delete');

        Route::get('emails', 'GmailController@index')->name('emails');
        Route::get('emails/sync', 'GmailController@sync')->name('emails.sync');
        Route::post('emails/task', 'GmailController@task')->name('emails.task');
        Route::post('emails/remove', 'GmailController@remove')->name('emails.remove');
        Route::post('emails/delete', 'GmailController@delete')->name('emails.delete');
        Route::post('emails/request', 'GmailController@requestAuthLink')->name('emails.request');
        Route::post('emails/token', 'GmailController@generateToken')->name('emails.token');

        Route::get('activities/{project_id}', 'ActivityController@index')->name('activities');

    });
});

