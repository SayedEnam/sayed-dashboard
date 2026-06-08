<?php

use Illuminate\Support\Facades\Route;
use Sayed\SayedDashboard\Http\Controllers\DashboardController;
use Sayed\SayedDashboard\Http\Controllers\UserController;

Route::group([
    'prefix' => config('sayed-dashboard.prefix', 'dashboard'),
    'middleware' => config('sayed-dashboard.middleware', ['web', 'auth', 'sayed.admin']),
    'as' => 'sayed.',
], function () {
    
    // Dashboard Home
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.home');
    
    // User Management Routes
    Route::resource('users', UserController::class);
    Route::get('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Profile Routes
    Route::get('profile', [DashboardController::class, 'profile'])->name('profile.edit');
    Route::put('profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
});

// Guest routes (no authentication required)
Route::group([
    'prefix' => config('sayed-dashboard.prefix', 'dashboard'),
    'middleware' => ['web', 'sayed.guest'],
    'as' => 'sayed.',
], function () {
    // You can add login page or public dashboard routes here
});