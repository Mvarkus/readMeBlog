<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Pages

Route::get('/', 'HomeController@index');
Route::get('/about', 'AboutController@index');

// User

Route::middleware('auth')->group(function () {

    Route::get('/user', 'User\UserController@index')->name('home');
    Route::get('/user/edit', 'User\UserController@editName');
    Route::get('/user/edit-email', 'User\UserController@editEmail');
    Route::put('/user', 'User\UserController@update');
    Route::put('/user-email', 'User\UserController@updateEmail');
    Route::delete('/user', 'User\UserController@destroy');
    Route::post('/user/avatar', 'User\UserAvatarController@create');
    Route::patch('/user/avatar/default', 'User\UserAvatarController@default');
    
});

Auth::routes(['reset' => false, 'confirm' => false]);

// Blog

Route::get('/posts', 'BlogController@index');
Route::get('/posts/{post}', 'BlogController@show')->name('showPost');

Route::post('/posts/{post}/comments', 'CommentController@store')
    ->middleware('auth');

Route::patch('/posts/{post}/vote', 'VoteController@vote')
    ->middleware('auth');

// Comments

Route::get('/comments/{comment}/edit', 'CommentController@edit')
    ->middleware(['auth', 'isCommentOwner']);

Route::patch('/comments/{comment}', 'CommentController@update')
    ->middleware(['auth', 'isCommentOwner']);

Route::delete('/comments/{comment}', 'CommentController@destroy')
    ->middleware(['auth', 'isCommentOwner']);


// Admin

Route::resource('/admin/categories', 'Admin\CategoryController');
Route::resource('/admin/posts', 'Admin\PostController');
Route::patch('comments/{comment}/approve', 'CommentController@approve')
    ->middleware(['auth', 'isAdmin']);

Route::patch('comments/{comment}/undo', 'CommentController@undoApproval')
    ->middleware(['auth', 'isAdmin']);
