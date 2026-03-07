<?php

// Rutas Admin — Monitoreo (Dashboard, PDF, Excel)

use App\Http\Controllers\Admin\MonitoringController;

Route::get('admin/monitoring', [MonitoringController::class, 'index'])->name('admin.monitoring.index');
Route::get('admin/monitoring/download/pdf', [MonitoringController::class, 'downloadMonitoringPDF'])->name('admin.monitoring.download.pdf');
Route::get('admin/monitoring/download/excel', [MonitoringController::class, 'downloadMonitoringExcel'])->name('admin.monitoring.download.excel');
