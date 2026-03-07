<?php

// Rutas Admin — Seguimiento Diario (CRUD + filtrado por pila + PDFs)

use App\Http\Controllers\Admin\TrackingController;

Route::resource('admin/tracking', TrackingController::class)->names([
    'index'   => 'admin.tracking.index',
    'create'  => 'admin.tracking.create',
    'store'   => 'admin.tracking.store',
    'show'    => 'admin.tracking.show',
    'edit'    => 'admin.tracking.edit',
    'update'  => 'admin.tracking.update',
    'destroy' => 'admin.tracking.destroy',
]);

Route::get('admin/tracking/composting/{composting}', [TrackingController::class, 'getByComposting'])->name('admin.tracking.by-composting');

Route::get('admin/tracking/download/all-pdf', [TrackingController::class, 'downloadAllTrackingsPDF'])->name('admin.tracking.download.all-pdf');
Route::get('admin/tracking/composting/{composting}/download/pdf', [TrackingController::class, 'downloadCompostingTrackingsPDF'])->name('admin.tracking.download.composting-pdf');
Route::get('admin/tracking/{tracking}/download/pdf', [TrackingController::class, 'downloadTrackingPDF'])->name('admin.tracking.download.pdf');
