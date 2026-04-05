<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class, 'store']);

Route::controller(LoginController::class)->group(function () {
    Route::post('/login', 'store');
    Route::middleware('jwt.refresh')->post('/refresh', 'refresh');
});
