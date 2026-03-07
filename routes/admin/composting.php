<?php

// Rutas Admin — Compostaje (CRUD + PDFs)

use App\Http\Controllers\Admin\CompostingController;

Route::resource('admin/composting', CompostingController::class)->names([
    'index'   => 'admin.composting.index',
    'create'  => 'admin.composting.create',
    'store'   => 'admin.composting.store',
    'show'    => 'admin.composting.show',
    'edit'    => 'admin.composting.edit',
    'update'  => 'admin.composting.update',
    'destroy' => 'admin.composting.destroy',
]);

Route::get('admin/composting/download/all-pdf', [CompostingController::class, 'downloadAllCompostingsPDF'])->name('admin.composting.download.all-pdf');
Route::get('admin/composting/{composting}/download/pdf', [CompostingController::class, 'downloadCompostingPDF'])->name('admin.composting.download.pdf');
