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


Route::prefix('/auth')->group(function(){
    Route::get('','App\Http\Controllers\LoginController@GetUserInfo');
    Route::post('', 'App\Http\Controllers\LoginController@Login');
    Route::delete('', 'App\Http\Controllers\LoginController@Logout');
});

Route::prefix('/post')->group(function(){
    ROUTE::post('','App\Http\Controllers\PostsController@CreatePost');

    ROUTE::post('/{targetId}/like','App\Http\Controllers\LikesController@SetLikesToPost');
    ROUTE::delete('/{targetId}/like','App\Http\Controllers\LikesController@SetLikesToPost');

    ROUTE::post('/{targetId}/comment','App\Http\Controllers\CommentController@SetCommentToPost');

    ROUTE::get('/{targetId}/comments','App\Http\Controllers\CommentController@GetPostComments');
    ROUTE::get('/search','App\Http\Controllers\PostsController@SearchPost');
});

ROUTE::prefix('/user')->group(function(){
    ROUTE::get('/{userid}/posts','App\Http\Controllers\PostsController@GetUserPosts');
    ROUTE::post('','App\Http\Controllers\UserController@CreateAccount');
    // ROUTE::get('/search','App\Http\Controllers\UserController@SearchUser');

});

ROUTE::get('/posts','App\Http\Controllers\PostsController@GetPosts');


