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

Route::group(
    [
        'middleware' => 'api',
        'namespace'  => 'App\Http\Controllers',
        'prefix'     => 'v1/auth',
    ],
    function ($router) {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::post('logout', 'AuthController@logout');
        Route::get('profile', 'AuthController@profile');
        Route::get('update', 'AuthController@update');
        Route::post('refresh', 'AuthController@refresh');
        Route::get('listUsers', 'AuthController@list');
        Route::get('listPosts', 'BlogPostController@list');
        Route::get('addPost', 'BlogPostController@store');
        Route::get('updatePost', 'BlogPostController@update');
        Route::get('deletePost', 'BlogPostController@destroy');
        Route::get('addComment', 'PostCommentController@store');
        Route::get('updateComment', 'PostCommentController@update');
        Route::get('deleteComment', 'PostCommentController@destroy');
    }
);

Route::group(
    [
        'middleware' => 'api',
        'namespace'  => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::resource('todos', 'TodoController');
    }
);

