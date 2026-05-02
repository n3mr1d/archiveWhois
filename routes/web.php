<?php

use App\Http\Controllers\PastebinController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ValidateGate;
use Illuminate\Support\Facades\Route;

// Home / Welcome
Route::get('/', function () {
    $trending = \App\Models\Pastebin::with(['slug', 'categories'])
        ->trending()
        ->limit(6)
        ->get();

    $recentPastes = \App\Models\Pastebin::with(['slug', 'categories'])
        ->recent()
        ->limit(6)
        ->get();

    $stats = [
        'total' => \App\Models\Pastebin::public()->notExpired()->count(),
        'views' => \App\Models\Pastebin::public()->sum('views'),
        'tags' => \App\Models\Category::count(),
    ];

    return view('welcome', compact('trending', 'recentPastes', 'stats'));
});

// Search Engine
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
Route::get('/search/stats', [SearchController::class, 'stats'])->name('search.stats');

// Pastebin
Route::get('/create', [PastebinController::class, 'create'])->name('pastebin.create');
Route::post('/create', [PastebinController::class, 'store'])->name('pastebin.store');
Route::get('/p/{slug}', [PastebinController::class, 'show'])->name('pastebin.show');
Route::post('/p/{slug}', [PastebinController::class, 'show'])->name('pastebin.show.password');


//Gate 
Route::prefix('login')->group(function () {
    Route::get('/', [ValidateGate::class, 'index'])->name('login');

    Route::post('/', [ValidateGate::class, 'validateLogin'])->name('login.post');
});

Route::prefix('register')->group(function () {
    Route::get('/', [ValidateGate::class, 'registerIndex'])->name('register');
    Route::post('/', [ValidateGate::class, 'validateRegister'])->name('register.post');
});


