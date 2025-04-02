<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rotas API com prefixo '/api' e middleware 'api'
|
*/

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{id}', [UserController::class, 'show'])->name('users.show')->where('id', '[0-9]+');
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update')->where('id', '[0-9]+');
    Route::patch('/{id}', [UserController::class, 'update'])->name('users.patch');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy')->where('id', '[0-9]+');
});

// Rotas para Posts
Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('posts.index');
    Route::post('/', [PostController::class, 'store'])->name('posts.store');
    Route::get('/{id}', [PostController::class, 'show'])->name('posts.show')->where('id', '[0-9]+');
    Route::put('/{id}', [PostController::class, 'update'])->name('posts.update')->where('id', '[0-9]+');
    Route::patch('/{id}', [PostController::class, 'update'])->name('posts.patch');
    Route::delete('/{id}', [PostController::class, 'destroy'])->name('posts.destroy')->where('id', '[0-9]+');
    Route::post('/{id}/tags', [PostController::class, 'attachTags'])->name('posts.tags.attach');
});

// Rotas para Tags
Route::prefix('tags')->group(function () {
    Route::get('/', [TagController::class, 'index'])->name('tags.index');
    Route::post('/', [TagController::class, 'store'])->name('tags.store');
    Route::get('/{id}', [TagController::class, 'show'])->name('tags.show')->where('id', '[0-9]+');
    Route::put('/{id}', [TagController::class, 'update'])->name('tags.update')->where('id', '[0-9]+');
    Route::patch('/{id}', [TagController::class, 'update'])->name('tags.patch');
    Route::delete('/{id}', [TagController::class, 'destroy'])->name('tags.destroy')->where('id', '[0-9]+');
    Route::get('/{id}/posts', [TagController::class, 'posts'])->name('tags.posts');
});

// Rota de teste
Route::get('/status', function () {
    return response()->json(['status' => 'API Online']);
});
