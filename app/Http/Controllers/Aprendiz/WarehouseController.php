<?php

// Controlador Aprendiz WarehouseController — Bodega (vista aprendiz)
namespace App\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use App\Models\WarehouseClassification;
use Illuminate\Http\Request;

// Bodega - Aprendiz (solo lectura)
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
        return view('aprendiz.warehouse.index', compact('inventory', 'recentMovements'));
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
        return view('aprendiz.warehouse.inventory', compact('inventory', 'movements', 'type', 'typeInSpanish'));
    }

    // Detalle de un movimiento individual.
    public function show(WarehouseClassification $warehouse)
    {
        // Mostrar vista
        return view('aprendiz.warehouse.show', compact('warehouse'));
    }
}
