<?php

use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/', [RegisterController::class, 'store']);
Route::get('/', [RegisterController::class, 'create']);
