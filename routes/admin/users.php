<?php

// Rutas Admin — Gestión de Usuarios (CRUD + activar/desactivar + PDFs)

use App\Http\Controllers\Admin\UserController;

Route::resource('admin/users', UserController::class)->names([
    'index'   => 'admin.users.index',
    'create'  => 'admin.users.create',
    'store'   => 'admin.users.store',
    'show'    => 'admin.users.show',
    'edit'    => 'admin.users.edit',
    'update'  => 'admin.users.update',
    'destroy' => 'admin.users.destroy',
]);

Route::post('admin/users/{user}/activate', [UserController::class, 'activate'])->name('admin.users.activate');
Route::get('admin/users/{user}/data', [UserController::class, 'getUserData'])->name('admin.users.data');

Route::get('admin/users/download/all-pdf', [UserController::class, 'downloadAllUsersPDF'])->name('admin.users.download.all-pdf');
Route::get('admin/users/{user}/download/pdf', [UserController::class, 'downloadUserPDF'])->name('admin.users.download.pdf');
