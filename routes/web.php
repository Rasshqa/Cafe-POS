<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SettingController;

// ═══════════ AUTH (Guest Only) ═══════════
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post')->middleware('throttle:5,1');
    // Rate limit: max 5 attempts per minute
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ═══════════ PROTECTED ROUTES ═══════════
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [PosController::class, 'dashboard'])->name('dashboard');

    // ─── Kasir (admin + kasir) ───
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
    Route::get('/pos/receipt/{id}', [PosController::class, 'receipt'])->name('pos.receipt');

    // ─── Admin Only ───
    Route::middleware('role:admin')->group(function () {
        // CRUD
        Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
        Route::resource('products', ProductController::class)->except(['create', 'show', 'edit']);

        // Supplier & Pembelian
        Route::resource('suppliers', SupplierController::class)->except(['create', 'show', 'edit']);
        Route::resource('purchases', PurchaseController::class)->only(['index', 'create', 'store', 'show']);

        // Riwayat & Laporan
        Route::get('/history', [PosController::class, 'history'])->name('pos.history');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

        // Settings (Profile Toko Aktif)
        Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');

        // Manajemen Karyawan
        Route::resource('users', \App\Http\Controllers\UserController::class)->except(['show']);
    });

    // ─── Owner Only ───
    Route::middleware('role:owner')->group(function () {
        Route::resource('stores', \App\Http\Controllers\StoreController::class)->except(['show']);
    });
    
    // Switch Store Route for Owner
    Route::get('/switch-store/{id}', [\App\Http\Controllers\StoreController::class, 'switchStore'])->name('stores.switch')->middleware('role:owner');
});
