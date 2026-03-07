<?php

// Rutas Aprendiz — Bodega (listado, detalle, inventario por tipo)

use App\Http\Controllers\Aprendiz\WarehouseController;

Route::get('aprendiz/warehouse', [WarehouseController::class, 'index'])->name('aprendiz.warehouse.index');
Route::get('aprendiz/warehouse/{warehouse}', [WarehouseController::class, 'show'])->name('aprendiz.warehouse.show');
Route::get('aprendiz/warehouse/inventory/{type}', [WarehouseController::class, 'inventory'])->name('aprendiz.warehouse.inventory');
