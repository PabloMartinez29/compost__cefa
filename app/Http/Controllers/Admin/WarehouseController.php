<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WarehouseClassification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            ->limit(10)
            ->get();

        return view('admin.warehouse.index', compact('inventory', 'recentMovements'));
    }


    /**
     * Display the specified resource.
     */
    public function show(WarehouseClassification $warehouse)
    {
        return view('admin.warehouse.show', compact('warehouse'));
    }

    /**
     * Show inventory by type
     */
    public function inventory($type)
    {
        $inventory = WarehouseClassification::getCurrentInventory($type);
        $movements = WarehouseClassification::where('type', $type)
            ->orderBy('created_at', 'desc')
            ->get();

        $typeInSpanish = [
            'Kitchen' => 'Cocina',
            'Beds' => 'Camas',
            'Leaves' => 'Hojas',
            'CowDung' => 'Estiércol de Vaca',
            'ChickenManure' => 'Estiércol de Pollo',
            'PigManure' => 'Estiércol de Cerdo',
            'Other' => 'Otros'
        ];

        return view('admin.warehouse.inventory', compact('inventory', 'movements', 'type', 'typeInSpanish'));
    }
}
