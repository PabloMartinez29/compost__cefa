<?php

// Rutas Aprendiz — Fertilizantes (CRUD + permisos + PDFs)

use App\Http\Controllers\Aprendiz\FertilizerController;

Route::resource('aprendiz/fertilizer', FertilizerController::class)->names([
    'index'   => 'aprendiz.fertilizer.index',
    'create'  => 'aprendiz.fertilizer.create',
    'store'   => 'aprendiz.fertilizer.store',
    'show'    => 'aprendiz.fertilizer.show',
    'edit'    => 'aprendiz.fertilizer.edit',
    'update'  => 'aprendiz.fertilizer.update',
    'destroy' => 'aprendiz.fertilizer.destroy',
]);

Route::get('aprendiz/fertilizer/download/all-pdf', [FertilizerController::class, 'downloadAllFertilizersPDF'])->name('aprendiz.fertilizer.download.all-pdf');
Route::get('aprendiz/fertilizer/{fertilizer}/download/pdf', [FertilizerController::class, 'downloadFertilizerPDF'])->name('aprendiz.fertilizer.download.pdf');

Route::post('aprendiz/fertilizer/{fertilizer}/request-delete', [FertilizerController::class, 'requestDeletePermission'])->name('aprendiz.fertilizer.request-delete');
