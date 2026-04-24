<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasteController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [PasteController::class, 'home'])->name('home');
Route::get('/new', [PasteController::class, 'create'])->name('paste.create');
Route::get('/search', [PasteController::class, 'search'])->name('paste.search');
Route::get('/trending', [PasteController::class, 'trending'])->name('paste.trending');
Route::get('/{slug}/raw', [PasteController::class, 'raw'])->name('paste.raw');
Route::get('/{slug}', [PasteController::class, 'show'])->name('paste.show');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/auth/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/auth/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/auth/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/auth/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [PasteController::class, 'dashboard'])->name('dashboard');
});
