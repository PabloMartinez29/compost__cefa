<?php

// Rutas Admin — Dashboard y Soporte

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupportController;

Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('dashboard.admin');
Route::post('/soporte/upload', [SupportController::class, 'upload'])->name('soporte.upload');
