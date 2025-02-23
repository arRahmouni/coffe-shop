<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use app\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoryController;

Route::controller(AuthController::class)->group(function () {
    Route::post('register'              , 'register');
    Route::post('login'                 , 'login');
    Route::post('logout'                , 'logout')->middleware('auth:sanctum');
    Route::get('email/verify/{token}'   , 'verifyEmail');
    Route::post('email/resend'          , 'resendVerificationEmail')->middleware(['throttle:3,1']);
    Route::post('forgot-password'       , 'sendResetPasswordLink');//->middleware(['throttle:3,1']);
    Route::post('reset-password'        , 'resetPassword');
});

Route::get('user', [UserController::class, 'show'])->middleware('auth:sanctum');

Route::controller(CategoryController::class)->prefix('categories')->middleware('auth:sanctum')->group(function () {
    Route::get('/'          , 'index');
    Route::post('/'         , 'store');
    Route::put('/{id}'      , 'update');
    Route::delete('/{id}'   , 'destroy');
});
