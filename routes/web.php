<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\AdminUserController;

// 1. PUBLIC AUTH ROUTES (Login, Google, Phone)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

    // Google Routes
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

    // Phone Route
    Route::post('/login/phone', [AuthController::class, 'loginWithPhone'])->name('login.phone');
});

// 2. PROTECTED APPLICATION ROUTES (Requires Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/repair/logout', [AuthController::class, 'logout'])->name('repair.logout');

    // READ-ONLY & DASHBOARD ROUTES (Accessible by all logged-in users)
    Route::get('/', [RepairController::class, 'index'])->name('repair.dashboard');
    Route::get('/repair', [RepairController::class, 'index'])->name('repair.dashboard.alt');
    Route::get('/repair/view', [RepairController::class, 'view'])->name('repair.view');

    // REPORT SUBMISSION ROUTES (For Filing Reports)
    Route::get('/repair/create', [RepairController::class, 'create'])->name('repair.create');
    Route::post('/repair/store', [RepairController::class, 'store'])
        ->middleware('throttle:daily-repairs')
        ->name('repair.store');

    // 3. ADMIN-ONLY MANAGEMENT ROUTES (Restricted to Admin Role)
    Route::middleware(['auth', 'admin'])->group(function () {
    Route::patch('/admin/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('admin.users.updateRole');
});
    Route::middleware('admin')->group(function () {
        // Admin Dashboard Route
        Route::get('/admin/dashboard', [RepairController::class, 'adminDashboard'])->name('admin.dashboard');

        // Admin Status & Triage Update Route
        Route::patch('/admin/repair/{id}/status', [RepairController::class, 'updateStatus'])->name('admin.repair.updateStatus');

        // Existing edit, update, delete routes
        Route::get('/repair/{id}/edit', [RepairController::class, 'edit'])->name('repair.edit');
        Route::put('/admin/dashboard/{id}', [RepairController::class, 'update'])->name('repair.update');
        Route::delete('/admin/dashboard/{id}', [RepairController::class, 'destroy'])->name('repair.destroy');
    });
});