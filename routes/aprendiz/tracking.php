<?php

// Rutas Aprendiz — Seguimiento Diario (CRUD + filtrado por pila + permisos + PDFs)

use App\Http\Controllers\Aprendiz\TrackingController;

Route::resource('aprendiz/tracking', TrackingController::class)->names([
    'index'   => 'aprendiz.tracking.index',
    'create'  => 'aprendiz.tracking.create',
    'store'   => 'aprendiz.tracking.store',
    'show'    => 'aprendiz.tracking.show',
    'edit'    => 'aprendiz.tracking.edit',
    'update'  => 'aprendiz.tracking.update',
    'destroy' => 'aprendiz.tracking.destroy',
]);

Route::get('aprendiz/tracking/composting/{composting}', [TrackingController::class, 'getByComposting'])->name('aprendiz.tracking.by-composting');

Route::post('aprendiz/tracking/{tracking}/request-delete-permission', [TrackingController::class, 'requestDeletePermission'])->name('aprendiz.tracking.request-delete-permission');

Route::get('aprendiz/tracking/download/all-pdf', [TrackingController::class, 'downloadAllTrackingsPDF'])->name('aprendiz.tracking.download.all-pdf');
Route::get('aprendiz/tracking/composting/{composting}/download/pdf', [TrackingController::class, 'downloadCompostingTrackingsPDF'])->name('aprendiz.tracking.download.composting-pdf');
Route::get('aprendiz/tracking/{tracking}/download/pdf', [TrackingController::class, 'downloadTrackingPDF'])->name('aprendiz.tracking.download.pdf');
