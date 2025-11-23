<?php

namespace App\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use App\Models\WarehouseClassification;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventory = WarehouseClassification::getInventoryByType();
        $recentMovements = WarehouseClassification::with([])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('aprendiz.warehouse.index', compact('inventory', 'recentMovements'));
    }

    /**
     * Show inventory by type
     */
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

        return view('aprendiz.warehouse.inventory', compact('inventory', 'movements', 'type', 'typeInSpanish'));
    }

    /**
     * Show the specified resource.
     */
    public function show(WarehouseClassification $warehouse)
    {
        return view('aprendiz.warehouse.show', compact('warehouse'));
    }
}
