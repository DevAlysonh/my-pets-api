<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});

Route::controller(PetController::class)->middleware('auth:api')
	->group(function () {
		Route::post('pets', 'store');
	});
