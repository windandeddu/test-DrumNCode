<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('/auth/register', [AuthController::class, 'createUser'])->withoutMiddleware(['auth:sanctum']);
Route::post('/auth/login', [AuthController::class, 'loginUser'])->withoutMiddleware(['auth:sanctum']);
