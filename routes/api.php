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

Route::post('/user','App\Http\Controllers\UserController@createAccount');
Route::put('/user',function(Request $request){
    return 'test api';
});
Route::get('/auth','App\Http\Controllers\LoginController@GetUserInfo');
Route::post('/auth', 'App\Http\Controllers\LoginController@Login');
Route::delete('/auth', 'App\Http\Controllers\LoginController@Logout');