<?php

// Rutas Admin — Fertilizantes (CRUD + PDFs)

use App\Http\Controllers\Admin\FertilizerController;

Route::resource('admin/fertilizer', FertilizerController::class)->names([
    'index'   => 'admin.fertilizer.index',
    'create'  => 'admin.fertilizer.create',
    'store'   => 'admin.fertilizer.store',
    'show'    => 'admin.fertilizer.show',
    'edit'    => 'admin.fertilizer.edit',
    'update'  => 'admin.fertilizer.update',
    'destroy' => 'admin.fertilizer.destroy',
]);

Route::get('admin/fertilizer/download/all-pdf', [FertilizerController::class, 'downloadAllFertilizersPDF'])->name('admin.fertilizer.download.all-pdf');
Route::get('admin/fertilizer/{fertilizer}/download/pdf', [FertilizerController::class, 'downloadFertilizerPDF'])->name('admin.fertilizer.download.pdf');
