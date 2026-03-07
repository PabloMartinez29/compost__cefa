<?php

// Rutas Aprendiz — Residuos Orgánicos (CRUD + permisos + PDFs)

use App\Http\Controllers\Aprendiz\OrganicController;

Route::resource('aprendiz/organic', OrganicController::class)->names([
    'index'   => 'aprendiz.organic.index',
    'create'  => 'aprendiz.organic.create',
    'store'   => 'aprendiz.organic.store',
    'show'    => 'aprendiz.organic.show',
    'edit'    => 'aprendiz.organic.edit',
    'update'  => 'aprendiz.organic.update',
    'destroy' => 'aprendiz.organic.destroy',
]);

Route::post('aprendiz/organic/{organic}/request-delete', [OrganicController::class, 'requestDeletePermission'])->name('aprendiz.organic.request-delete');
Route::post('aprendiz/organic/{organic}/request-edit', [OrganicController::class, 'requestEditPermission'])->name('aprendiz.organic.request-edit');

Route::get('aprendiz/organic/download/all-pdf', [OrganicController::class, 'downloadAllOrganicsPDF'])->name('aprendiz.organic.download.all-pdf');
Route::get('aprendiz/organic/{organic}/download/pdf', [OrganicController::class, 'downloadOrganicPDF'])->name('aprendiz.organic.download.pdf');
