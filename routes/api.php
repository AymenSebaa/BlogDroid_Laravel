<?php

use Illuminate\Http\Request;

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
// users
Route::post('alive', 'Api\AuthController@alive')->middleware('JWTAuth');
Route::post('login', 'Api\AuthController@login');
Route::post('register', 'Api\AuthController@register');
Route::post('save_user_info', 'Api\AuthController@saveUserInfo')->middleware('JWTAuth');
Route::post('logout', 'Api\AuthController@logout');

// posts
Route::post('posts/create', 'Api\PostController@create')->middleware('JWTAuth');
Route::post('posts/update', 'Api\PostController@update')->middleware('JWTAuth');
Route::post('posts/delete', 'Api\PostController@delete')->middleware('JWTAuth');
Route::post('posts', 'Api\PostController@posts')->middleware('JWTAuth');


// comments
Route::post('comments/create', 'Api\CommentController@create')->middleware('JWTAuth');
Route::post('comments/update', 'Api\CommentController@update')->middleware('JWTAuth');
Route::post('comments/delete', 'Api\CommentController@delete')->middleware('JWTAuth');
Route::post('posts/comments', 'Api\CommentController@comments')->middleware('JWTAuth');

// likes
Route::post('posts/like', 'Api\LikeController@like')->middleware('JWTAuth');