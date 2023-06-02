<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;




Route::group([

    'middleware' => ['api', 'auth:api'],
    'prefix' => 'auth'
 
 ], function ($router) {
 
     Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['auth:api']);
     Route::post('logout', [AuthController::class, 'logout']);
     Route::post('refresh', [AuthController::class, 'refresh'])->withoutMiddleware(['auth:api']);
     Route::get('user', [AuthController::class, 'me']);
     Route::post('register', [AuthController::class, 'register'])->withoutMiddleware(['auth:api']);
 
 });
 
 //User Routes
Route::get('user', [UserController::class, 'show']);
Route::put('/user/{id}', [UserController::class, 'update']);
Route::delete('/user/{id}', [UserController::class, 'delete']);

//Post Routes
Route::get('posts', [PostController::class, 'show']);
Route::post('posts/create', [PostController::class, 'create']);
Route::delete('/posts/{id}', [PostController::class, 'delete']);
Route::put('/posts/{id}', [PostController::class, 'update']);

//Category Routes
Route::get('categories', [CategoryController::class, 'show']);
Route::post('categories/create', [CategoryController::class, 'create']);
Route::delete('categories/{id}', [CategoryController::class, 'delete']);
Route::put('categories/{id}', [CategoryController::class, 'update']);
