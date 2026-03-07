<?php

// Controlador Admin WarehouseController — Gestión de bodega e inventario
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WarehouseClassification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// Bodega - Admin (solo lectura)
class WarehouseController extends Controller
{
    // Dashboard: inventario por tipo y últimos 10 movimientos.
    public function index()
    {
        $inventory = WarehouseClassification::getInventoryByType();
        $recentMovements = WarehouseClassification::with([])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Mostrar vista
        return view('admin.warehouse.index', compact('inventory', 'recentMovements'));
    }

    // Detalle de un movimiento individual.
    public function show(WarehouseClassification $warehouse)
    {
        // Mostrar vista
        return view('admin.warehouse.show', compact('warehouse'));
    }

    // Inventario filtrado por tipo de residuo con movimientos paginados.
    public function inventory($type)
    {
        $inventory = WarehouseClassification::getCurrentInventory($type);
        $movements = WarehouseClassification::where('type', $type)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $typeInSpanish = [
            'Kitchen' => 'Cocina',
            'Beds' => 'Camas',
            'Leaves' => 'Hojas',
            'CowDung' => 'Estiércol de Vaca',
            'ChickenManure' => 'Estiércol de Pollo',
            'PigManure' => 'Estiércol de Cerdo',
            'Other' => 'Otros'
        ];

        // Mostrar vista
        return view('admin.warehouse.inventory', compact('inventory', 'movements', 'type', 'typeInSpanish'));
    }
}
