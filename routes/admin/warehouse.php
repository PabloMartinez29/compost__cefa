<?php

// Rutas Admin — Bodega (listado, detalle, inventario por tipo)

use App\Http\Controllers\Admin\WarehouseController;

Route::get('admin/warehouse', [WarehouseController::class, 'index'])->name('admin.warehouse.index');
Route::get('admin/warehouse/{warehouse}', [WarehouseController::class, 'show'])->name('admin.warehouse.show');
Route::get('admin/warehouse/inventory/{type}', [WarehouseController::class, 'inventory'])->name('admin.warehouse.inventory');
