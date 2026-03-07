<?php

// Rutas Aprendiz — Maquinaria (4 submódulos: Maquinaria, Proveedores, Mantenimiento, Control de Uso)

use App\Http\Controllers\Aprendiz\MachineryController;
use App\Http\Controllers\Aprendiz\SupplierController;
use App\Http\Controllers\Aprendiz\MaintenanceController;
use App\Http\Controllers\Aprendiz\UsageControlController;

// 1. Maquinaria — Identificación y Especificaciones
Route::resource('aprendiz/machinery/machineries', MachineryController::class)->names([
    'index'   => 'aprendiz.machinery.index',
    'create'  => 'aprendiz.machinery.create',
    'store'   => 'aprendiz.machinery.store',
    'show'    => 'aprendiz.machinery.show',
    'edit'    => 'aprendiz.machinery.edit',
    'update'  => 'aprendiz.machinery.update',
    'destroy' => 'aprendiz.machinery.destroy',
]);

Route::post('aprendiz/machinery/machineries/{machinery}/request-delete', [MachineryController::class, 'requestDeletePermission'])->name('aprendiz.machinery.request-delete');
Route::get('aprendiz/machinery/machineries/{machinery}/check-delete-status', [MachineryController::class, 'checkDeletePermissionStatus'])->name('aprendiz.machinery.check-delete-status');

Route::get('aprendiz/machinery/machineries/download/all-pdf', [MachineryController::class, 'downloadAllMachineriesPDF'])->name('aprendiz.machinery.download.all-pdf');
Route::get('aprendiz/machinery/machineries/{machinery}/download/pdf', [MachineryController::class, 'downloadMachineryPDF'])->name('aprendiz.machinery.download.pdf');

// 2. Proveedores
Route::get('aprendiz/machinery/supplier', [SupplierController::class, 'index'])->name('aprendiz.machinery.supplier.index');
Route::get('aprendiz/machinery/supplier/create', [SupplierController::class, 'create'])->name('aprendiz.machinery.supplier.create');
Route::post('aprendiz/machinery/supplier', [SupplierController::class, 'store'])->name('aprendiz.machinery.supplier.store');
Route::get('aprendiz/machinery/supplier/{supplier}', [SupplierController::class, 'show'])->name('aprendiz.machinery.supplier.show');
Route::get('aprendiz/machinery/supplier/{supplier}/edit', [SupplierController::class, 'edit'])->name('aprendiz.machinery.supplier.edit');
Route::put('aprendiz/machinery/supplier/{supplier}', [SupplierController::class, 'update'])->name('aprendiz.machinery.supplier.update');
Route::delete('aprendiz/machinery/supplier/{supplier}', [SupplierController::class, 'destroy'])->name('aprendiz.machinery.supplier.destroy');

Route::post('aprendiz/machinery/supplier/{supplier}/request-delete', [SupplierController::class, 'requestDeletePermission'])->name('aprendiz.machinery.supplier.request-delete');
Route::get('aprendiz/machinery/supplier/{supplier}/check-delete-status', [SupplierController::class, 'checkDeletePermissionStatus'])->name('aprendiz.machinery.supplier.check-delete-status');

Route::get('aprendiz/machinery/supplier/download/all-pdf', [SupplierController::class, 'downloadAllSuppliersPDF'])->name('aprendiz.machinery.supplier.download.all-pdf');
Route::get('aprendiz/machinery/supplier/{supplier}/download/pdf', [SupplierController::class, 'downloadSupplierPDF'])->name('aprendiz.machinery.supplier.download.pdf');

// 3. Control de Actividades / Mantenimiento
Route::get('aprendiz/machinery/maintenance', [MaintenanceController::class, 'index'])->name('aprendiz.machinery.maintenance.index');
Route::get('aprendiz/machinery/maintenance/next-due', [MaintenanceController::class, 'nextMaintenanceDue'])->name('aprendiz.machinery.maintenance.next-due');
Route::get('aprendiz/machinery/maintenance/create', [MaintenanceController::class, 'create'])->name('aprendiz.machinery.maintenance.create');
Route::post('aprendiz/machinery/maintenance', [MaintenanceController::class, 'store'])->name('aprendiz.machinery.maintenance.store');
Route::get('aprendiz/machinery/maintenance/{maintenance}', [MaintenanceController::class, 'show'])->name('aprendiz.machinery.maintenance.show');
Route::get('aprendiz/machinery/maintenance/{maintenance}/edit', [MaintenanceController::class, 'edit'])->name('aprendiz.machinery.maintenance.edit');
Route::put('aprendiz/machinery/maintenance/{maintenance}', [MaintenanceController::class, 'update'])->name('aprendiz.machinery.maintenance.update');
Route::delete('aprendiz/machinery/maintenance/{maintenance}', [MaintenanceController::class, 'destroy'])->name('aprendiz.machinery.maintenance.destroy');

Route::post('aprendiz/machinery/maintenance/{maintenance}/request-delete', [MaintenanceController::class, 'requestDeletePermission'])->name('aprendiz.machinery.maintenance.request-delete');
Route::get('aprendiz/machinery/maintenance/{maintenance}/check-delete-status', [MaintenanceController::class, 'checkDeletePermissionStatus'])->name('aprendiz.machinery.maintenance.check-delete-status');

Route::get('aprendiz/machinery/maintenance/download/all-pdf', [MaintenanceController::class, 'downloadAllMaintenancesPDF'])->name('aprendiz.machinery.maintenance.download.all-pdf');
Route::get('aprendiz/machinery/maintenance/{maintenance}/download/pdf', [MaintenanceController::class, 'downloadMaintenancePDF'])->name('aprendiz.machinery.maintenance.download.pdf');

// 4. Control de Uso
Route::get('aprendiz/machinery/usage-control', [UsageControlController::class, 'index'])->name('aprendiz.machinery.usage-control.index');
Route::get('aprendiz/machinery/usage-control/create', [UsageControlController::class, 'create'])->name('aprendiz.machinery.usage-control.create');
Route::post('aprendiz/machinery/usage-control', [UsageControlController::class, 'store'])->name('aprendiz.machinery.usage-control.store');
Route::get('aprendiz/machinery/usage-control/{usageControl}', [UsageControlController::class, 'show'])->name('aprendiz.machinery.usage-control.show');
Route::get('aprendiz/machinery/usage-control/{usageControl}/edit', [UsageControlController::class, 'edit'])->name('aprendiz.machinery.usage-control.edit');
Route::put('aprendiz/machinery/usage-control/{usageControl}', [UsageControlController::class, 'update'])->name('aprendiz.machinery.usage-control.update');
Route::delete('aprendiz/machinery/usage-control/{usageControl}', [UsageControlController::class, 'destroy'])->name('aprendiz.machinery.usage-control.destroy');

Route::post('aprendiz/machinery/usage-control/{usageControl}/request-delete', [UsageControlController::class, 'requestDeletePermission'])->name('aprendiz.machinery.usage-control.request-delete');
Route::get('aprendiz/machinery/usage-control/{usageControl}/check-delete-status', [UsageControlController::class, 'checkDeletePermissionStatus'])->name('aprendiz.machinery.usage-control.check-delete-status');

Route::get('aprendiz/machinery/usage-control/download/all-pdf', [UsageControlController::class, 'downloadAllUsageControlsPDF'])->name('aprendiz.machinery.usage-control.download.all-pdf');
Route::get('aprendiz/machinery/usage-control/{usageControl}/download/pdf', [UsageControlController::class, 'downloadUsageControlPDF'])->name('aprendiz.machinery.usage-control.download.pdf');
