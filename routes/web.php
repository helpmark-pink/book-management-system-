<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookSearchController;
use App\Http\Controllers\ReadingRecordController;
use App\Http\Controllers\ReviewController;

// Test page
Route::get('/test', function () {
    return view('test');
});

Route::get('/', function () {
    return view('home');
})->name('home');

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Book search and add
    Route::get('/books', [BookSearchController::class, 'list'])->name('web.books.list');
    Route::get('/books/search', [BookSearchController::class, 'index'])->name('books.search');
    Route::get('/books/search/results', [BookSearchController::class, 'search'])->name('books.search.results');
    Route::post('/books/add', [BookSearchController::class, 'add'])->name('books.add');

    // Reading records management
    Route::get('/reading-records', [ReadingRecordController::class, 'index'])->name('web.reading-records.index');
    Route::put('/reading-records/{record}', [ReadingRecordController::class, 'update'])->name('web.reading-records.update');
    Route::delete('/reading-records/{record}', [ReadingRecordController::class, 'destroy'])->name('web.reading-records.destroy');

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('web.reviews.index');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('web.reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('web.reviews.destroy');
});
