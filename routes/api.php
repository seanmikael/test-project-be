<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;



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
 
Route::get('user', [UserController::class, 'show']);
Route::put('/user/{id}', [UserController::class, 'update']);
Route::delete('/user/{id}', [UserController::class, 'delete']);