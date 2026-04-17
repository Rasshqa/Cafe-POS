<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [PosController::class, 'dashboard'])->name('dashboard');

// Fitur tanpa create & edit karena kita akan menggunakan modal Bootstrap sederhana nanti
Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
Route::resource('products', ProductController::class)->except(['create', 'show', 'edit']);

Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
Route::get('/history', [PosController::class, 'history'])->name('pos.history');
