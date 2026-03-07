<?php

// Rutas Aprendiz — Compostaje (CRUD + permisos + PDFs)

use App\Http\Controllers\Aprendiz\CompostingController;

Route::resource('aprendiz/composting', CompostingController::class)->names([
    'index'   => 'aprendiz.composting.index',
    'create'  => 'aprendiz.composting.create',
    'store'   => 'aprendiz.composting.store',
    'show'    => 'aprendiz.composting.show',
    'edit'    => 'aprendiz.composting.edit',
    'update'  => 'aprendiz.composting.update',
    'destroy' => 'aprendiz.composting.destroy',
]);

Route::post('aprendiz/composting/{composting}/request-edit', [CompostingController::class, 'requestEditPermission'])->name('aprendiz.composting.request-edit');
Route::post('aprendiz/composting/{composting}/request-delete', [CompostingController::class, 'requestDeletePermission'])->name('aprendiz.composting.request-delete');
Route::get('aprendiz/composting/{composting}/check-delete-status', [CompostingController::class, 'checkDeletePermissionStatus'])->name('aprendiz.composting.check-delete-status');

Route::get('aprendiz/composting/download/all-pdf', [CompostingController::class, 'downloadAllCompostingsPDF'])->name('aprendiz.composting.download.all-pdf');
Route::get('aprendiz/composting/{composting}/download/pdf', [CompostingController::class, 'downloadCompostingPDF'])->name('aprendiz.composting.download.pdf');
