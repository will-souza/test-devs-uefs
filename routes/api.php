<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;

Route::apiResource('users', UserController::class);
Route::apiResource('posts', PostController::class);
Route::apiResource('tags', TagController::class);
