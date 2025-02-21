<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use app\Http\Controllers\Api\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::post('register'  ,  'register');
    Route::post('login'     ,  'login');
    Route::post('logout'    ,  'logout')->middleware('auth:sanctum');
});

Route::get('user', [UserController::class, 'show'])->middleware('auth:sanctum');
