<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;

// Auth Routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Web App Routes (Dilindungi oleh auth middleware)
Route::middleware(['auth'])->group(function () {
    
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    
    Route::get('/dashboard', [PosController::class, 'dashboard'])->name('dashboard');
    
    // Resource routes untuk CRUD
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('products', ProductController::class)->except(['create', 'show', 'edit']);
    
    // Kasir & History
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
    Route::get('/history', [PosController::class, 'history'])->name('pos.history');
    Route::get('/pos/receipt/{id}', [PosController::class, 'receipt'])->name('pos.receipt');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    
});
