<?php

// Rutas Admin — Maquinaria (4 submódulos: Maquinaria, Proveedores, Mantenimiento, Control de Uso)

use App\Http\Controllers\Admin\MachineryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\UsageControlController;

// 1. Maquinaria — Identificación y Especificaciones
Route::resource('admin/machinery/machineries', MachineryController::class)->names([
    'index'   => 'admin.machinery.index',
    'create'  => 'admin.machinery.create',
    'store'   => 'admin.machinery.store',
    'show'    => 'admin.machinery.show',
    'edit'    => 'admin.machinery.edit',
    'update'  => 'admin.machinery.update',
    'destroy' => 'admin.machinery.destroy',
]);

Route::get('admin/machinery/machineries/download/all-pdf', [MachineryController::class, 'downloadAllMachineriesPDF'])->name('admin.machinery.download.all-pdf');
Route::get('admin/machinery/machineries/{machinery}/download/pdf', [MachineryController::class, 'downloadMachineryPDF'])->name('admin.machinery.download.pdf');

// 2. Proveedores
Route::get('admin/machinery/supplier', [SupplierController::class, 'index'])->name('admin.machinery.supplier.index');
Route::get('admin/machinery/supplier/create', [SupplierController::class, 'create'])->name('admin.machinery.supplier.create');
Route::post('admin/machinery/supplier', [SupplierController::class, 'store'])->name('admin.machinery.supplier.store');
Route::get('admin/machinery/supplier/{supplier}', [SupplierController::class, 'show'])->name('admin.machinery.supplier.show');
Route::get('admin/machinery/supplier/{supplier}/edit', [SupplierController::class, 'edit'])->name('admin.machinery.supplier.edit');
Route::put('admin/machinery/supplier/{supplier}', [SupplierController::class, 'update'])->name('admin.machinery.supplier.update');
Route::delete('admin/machinery/supplier/{supplier}', [SupplierController::class, 'destroy'])->name('admin.machinery.supplier.destroy');

Route::get('admin/machinery/supplier/download/all-pdf', [SupplierController::class, 'downloadAllSuppliersPDF'])->name('admin.machinery.supplier.download.all-pdf');
Route::get('admin/machinery/supplier/{supplier}/download/pdf', [SupplierController::class, 'downloadSupplierPDF'])->name('admin.machinery.supplier.download.pdf');

// 3. Control de Actividades / Mantenimiento
Route::get('admin/machinery/maintenance', [MaintenanceController::class, 'index'])->name('admin.machinery.maintenance.index');
Route::get('admin/machinery/maintenance/next-due', [MaintenanceController::class, 'nextMaintenanceDue'])->name('admin.machinery.maintenance.next-due');
Route::get('admin/machinery/maintenance/create', [MaintenanceController::class, 'create'])->name('admin.machinery.maintenance.create');
Route::post('admin/machinery/maintenance', [MaintenanceController::class, 'store'])->name('admin.machinery.maintenance.store');
Route::get('admin/machinery/maintenance/{maintenance}', [MaintenanceController::class, 'show'])->name('admin.machinery.maintenance.show');
Route::get('admin/machinery/maintenance/{maintenance}/edit', [MaintenanceController::class, 'edit'])->name('admin.machinery.maintenance.edit');
Route::put('admin/machinery/maintenance/{maintenance}', [MaintenanceController::class, 'update'])->name('admin.machinery.maintenance.update');
Route::delete('admin/machinery/maintenance/{maintenance}', [MaintenanceController::class, 'destroy'])->name('admin.machinery.maintenance.destroy');

Route::get('admin/machinery/maintenance/download/all-pdf', [MaintenanceController::class, 'downloadAllMaintenancesPDF'])->name('admin.machinery.maintenance.download.all-pdf');
Route::get('admin/machinery/maintenance/{maintenance}/download/pdf', [MaintenanceController::class, 'downloadMaintenancePDF'])->name('admin.machinery.maintenance.download.pdf');

// 4. Control de Uso
Route::get('admin/machinery/usage-control', [UsageControlController::class, 'index'])->name('admin.machinery.usage-control.index');
Route::get('admin/machinery/usage-control/create', [UsageControlController::class, 'create'])->name('admin.machinery.usage-control.create');
Route::post('admin/machinery/usage-control', [UsageControlController::class, 'store'])->name('admin.machinery.usage-control.store');
Route::get('admin/machinery/usage-control/{usageControl}', [UsageControlController::class, 'show'])->name('admin.machinery.usage-control.show');
Route::get('admin/machinery/usage-control/{usageControl}/edit', [UsageControlController::class, 'edit'])->name('admin.machinery.usage-control.edit');
Route::put('admin/machinery/usage-control/{usageControl}', [UsageControlController::class, 'update'])->name('admin.machinery.usage-control.update');
Route::delete('admin/machinery/usage-control/{usageControl}', [UsageControlController::class, 'destroy'])->name('admin.machinery.usage-control.destroy');

Route::get('admin/machinery/usage-control/download/all-pdf', [UsageControlController::class, 'downloadAllUsageControlsPDF'])->name('admin.machinery.usage-control.download.all-pdf');
Route::get('admin/machinery/usage-control/{usageControl}/download/pdf', [UsageControlController::class, 'downloadUsageControlPDF'])->name('admin.machinery.usage-control.download.pdf');
