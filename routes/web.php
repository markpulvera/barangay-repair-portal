<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RepairController;

// Public Auth Routes (Gmail & Phone)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    
    // Google Routes
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
    
    // Phone Route
    Route::post('/login/phone', [AuthController::class, 'loginWithPhone'])->name('login.phone');
});

// Protected Application Routes (Requires Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Open to all logged-in users (View Dashboard, Form, and Records)
    Route::get('/', [RepairController::class, 'index'])->name('repair.dashboard');
    Route::get('/repair', [RepairController::class, 'index'])->name('repair.dashboard.alt');
    Route::get('/repair/view', [RepairController::class, 'view'])->name('repair.view');
    Route::get('/repair/create', [RepairController::class, 'create'])->name('repair.create');
    
    // Store route with rate limiting
    Route::post('/repair/store', [RepairController::class, 'store'])
        ->middleware('throttle:daily-repairs')
        ->name('repair.store');

    // Admin-only management routes (Edit & Delete Access)
    Route::middleware('admin')->group(function () {
        Route::get('/repair/{id}/edit', [RepairController::class, 'edit'])->name('repair.edit');
        Route::put('/repair/{id}', [RepairController::class, 'update'])->name('repair.update');
        Route::delete('/repair/{id}', [RepairController::class, 'destroy'])->name('repair.destroy');
    });
});