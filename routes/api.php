<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\ReadingRecordController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Book routes
    Route::apiResource('books', BookController::class);
    Route::post('/books/search', [BookController::class, 'search']);

    // Reading record routes
    Route::apiResource('reading-records', ReadingRecordController::class);

    // Review routes
    Route::apiResource('reviews', ReviewController::class);
});
