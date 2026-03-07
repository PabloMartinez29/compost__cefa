<?php

// Rutas Admin — Residuos Orgánicos (CRUD + PDFs)

use App\Http\Controllers\Admin\OrganicController;

Route::resource('admin/organic', OrganicController::class)->names([
    'index'   => 'admin.organic.index',
    'create'  => 'admin.organic.create',
    'store'   => 'admin.organic.store',
    'show'    => 'admin.organic.show',
    'edit'    => 'admin.organic.edit',
    'update'  => 'admin.organic.update',
    'destroy' => 'admin.organic.destroy',
]);

Route::get('admin/organic/download/all-pdf', [OrganicController::class, 'downloadAllOrganicsPDF'])->name('admin.organic.download.all-pdf');
Route::get('admin/organic/{organic}/download/pdf', [OrganicController::class, 'downloadOrganicPDF'])->name('admin.organic.download.pdf');
