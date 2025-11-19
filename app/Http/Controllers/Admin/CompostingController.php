<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Composting;
use App\Models\Ingredient;
use App\Models\Organic;
use App\Models\WarehouseClassification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class CompostingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compostings = Composting::with(['ingredients.organic', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calcular estadísticas
        $totalPiles = Composting::count();
        $activePiles = Composting::whereNull('end_date')->count();
        $completedPiles = Composting::whereNotNull('end_date')->count();
        $totalIngredients = Composting::withCount('ingredients')->get()->sum('ingredients_count');

        return view('admin.composting.index', compact('compostings', 'totalPiles', 'activePiles', 'completedPiles', 'totalIngredients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener residuos orgánicos disponibles con stock
        $availableOrganics = Organic::all()->map(function ($organic) {
            $availableQuantity = WarehouseClassification::getCurrentInventory($organic->type);
            return [
                'id' => $organic->id,
                'type' => $organic->type,
                'type_in_spanish' => $organic->type_in_spanish,
                'formatted_weight' => $organic->formatted_weight,
                'available_quantity' => $availableQuantity
            ];
        })->filter(function ($organic) {
            return $organic['available_quantity'] > 0;
        })->values();

        return view('admin.composting.create', compact('availableOrganics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pile_num' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'total_kg' => 'nullable|numeric|min:0',
            'efficiency' => 'nullable|numeric|min:0|max:100',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.organic_id' => 'required|exists:organics,id',
            'ingredients.*.amount' => 'required|numeric|min:0.01',
            'ingredients.*.notes' => 'nullable|string|max:255'
        ]);

        // Validar que no exceda la cantidad disponible
        foreach ($request->ingredients as $ingredientData) {
            $organic = Organic::find($ingredientData['organic_id']);
            $availableQuantity = WarehouseClassification::getCurrentInventory($organic->type);
            
            if ($ingredientData['amount'] > $availableQuantity) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "La cantidad de {$organic->type_in_spanish} ({$ingredientData['amount']} kg) excede la cantidad disponible en bodega ({$availableQuantity} kg).");
            }
        }

        DB::beginTransaction();
        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('compostings', 'public');
            }
            
            // Crear el compostaje
            $composting = Composting::create([
                'pile_num' => $request->pile_num,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_kg' => $request->total_kg,
                'efficiency' => $request->efficiency,
                'image' => $imagePath,
                'created_by' => auth()->id()
            ]);

            // Crear los ingredientes y restar del inventario
            foreach ($request->ingredients as $ingredientData) {
                // Crear el ingrediente
                Ingredient::create([
                    'composting_id' => $composting->id,
                    'organic_id' => $ingredientData['organic_id'],
                    'amount' => $ingredientData['amount'],
                    'notes' => $ingredientData['notes'] ?? null
                ]);

                // Restar del inventario de bodega
                $organic = Organic::find($ingredientData['organic_id']);
                if ($organic) {
                    Log::info('Creating warehouse exit record for composting', [
                        'organic_id' => $ingredientData['organic_id'],
                        'organic_type' => $organic->type,
                        'amount' => $ingredientData['amount'],
                        'pile_num' => $composting->formatted_pile_num
                    ]);
                    
                    $warehouseRecord = WarehouseClassification::create([
                        'date' => $request->start_date,
                        'type' => $organic->type,
                        'movement_type' => 'exit',
                        'weight' => $ingredientData['amount'],
                        'notes' => "Uso en pila de compostaje #{$composting->formatted_pile_num}",
                        'processed_by' => auth()->user()->name
                    ]);
                    
                    Log::info('Warehouse record created successfully', [
                        'warehouse_id' => $warehouseRecord->id,
                        'type' => $warehouseRecord->type,
                        'movement_type' => $warehouseRecord->movement_type,
                        'weight' => $warehouseRecord->weight
                    ]);
                } else {
                    Log::warning('Organic not found for ingredient', [
                        'organic_id' => $ingredientData['organic_id']
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.composting.index')
                ->with('success', 'Pila de compostaje registrada exitosamente!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar la pila: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Composting $composting)
    {
        if (request()->wantsJson()) {
            $composting->load(['ingredients.organic', 'creator']);
            
            return response()->json([
                'id' => $composting->id,
                'pile_num' => $composting->pile_num,
                'formatted_pile_num' => $composting->formatted_pile_num,
                'start_date' => $composting->start_date,
                'formatted_start_date' => $composting->formatted_start_date,
                'end_date' => $composting->end_date,
                'formatted_end_date' => $composting->formatted_end_date,
                'total_kg' => $composting->total_kg,
                'formatted_total_kg' => $composting->formatted_total_kg,
                'efficiency' => $composting->efficiency,
                'formatted_efficiency' => $composting->formatted_efficiency,
                'total_ingredients' => $composting->total_ingredients,
                'formatted_total_ingredients' => $composting->formatted_total_ingredients,
                'total_ingredient_kg' => $composting->ingredients->sum('amount'),
                'formatted_total_ingredient_kg' => number_format($composting->ingredients->sum('amount'), 2) . ' Kg',
                'ingredients' => $composting->ingredients->map(function ($ingredient) {
                    return [
                        'id' => $ingredient->id,
                        'organic_id' => $ingredient->organic_id,
                        'amount' => $ingredient->amount,
                        'formatted_amount' => $ingredient->formatted_amount,
                        'notes' => $ingredient->notes,
                        'ingredient_name' => $ingredient->ingredient_name,
                        'organic' => [
                            'id' => $ingredient->organic->id,
                            'type' => $ingredient->organic->type,
                            'type_in_spanish' => $ingredient->organic->type_in_spanish,
                            'formatted_weight' => $ingredient->organic->formatted_weight
                        ]
                    ];
                }),
                'creator' => $composting->creator ? [
                    'id' => $composting->creator->id,
                    'name' => $composting->creator->name
                ] : null,
                'created_at' => $composting->created_at,
                'updated_at' => $composting->updated_at
            ]);
        }

        $composting->load(['ingredients.organic', 'creator']);
        return view('admin.composting.show', compact('composting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Composting $composting)
    {
        // Obtener residuos orgánicos disponibles con stock
        $availableOrganics = Organic::all()->map(function ($organic) {
            $availableQuantity = WarehouseClassification::getCurrentInventory($organic->type);
            return [
                'id' => $organic->id,
                'type' => $organic->type,
                'type_in_spanish' => $organic->type_in_spanish,
                'formatted_weight' => $organic->formatted_weight,
                'available_quantity' => $availableQuantity
            ];
        })->filter(function ($organic) {
            return $organic['available_quantity'] > 0;
        })->values();

        $composting->load(['ingredients.organic']);
        return view('admin.composting.edit', compact('composting', 'availableOrganics'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Composting $composting)
    {
        $request->validate([
            'pile_num' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'total_kg' => 'nullable|numeric|min:0',
            'efficiency' => 'nullable|numeric|min:0|max:100',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.organic_id' => 'required|exists:organics,id',
            'ingredients.*.amount' => 'required|numeric|min:0.01',
            'ingredients.*.notes' => 'nullable|string|max:255'
        ]);

        // Validar que no exceda la cantidad disponible
        foreach ($request->ingredients as $ingredientData) {
            $organic = Organic::find($ingredientData['organic_id']);
            $availableQuantity = WarehouseClassification::getCurrentInventory($organic->type);
            
            if ($ingredientData['amount'] > $availableQuantity) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "La cantidad de {$organic->type_in_spanish} ({$ingredientData['amount']} kg) excede la cantidad disponible en bodega ({$availableQuantity} kg).");
            }
        }

        DB::beginTransaction();
        try {
            // Handle image upload
            $data = [
                'pile_num' => $request->pile_num,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_kg' => $request->total_kg,
                'efficiency' => $request->efficiency
            ];
            
            if ($request->hasFile('image')) {
                // Eliminar imagen anterior si existe
                if ($composting->image && Storage::disk('public')->exists($composting->image)) {
                    Storage::disk('public')->delete($composting->image);
                }
                $data['image'] = $request->file('image')->store('compostings', 'public');
            }
            
            // Si se envía remove_image, eliminar la imagen
            if ($request->has('remove_image') && $request->remove_image == '1') {
                if ($composting->image && Storage::disk('public')->exists($composting->image)) {
                    Storage::disk('public')->delete($composting->image);
                }
                $data['image'] = null;
            }
            
            // Actualizar el compostaje
            $composting->update($data);

            // Obtener ingredientes existentes para devolver al inventario
            $existingIngredients = $composting->ingredients()->with('organic')->get();
            
            // Devolver al inventario las cantidades de ingredientes eliminados
            foreach ($existingIngredients as $existingIngredient) {
                WarehouseClassification::create([
                    'date' => $request->start_date,
                    'type' => $existingIngredient->organic->type,
                    'movement_type' => 'entry',
                    'weight' => $existingIngredient->amount,
                    'notes' => "Devolución por edición de pila #{$composting->formatted_pile_num}",
                    'processed_by' => auth()->user()->name
                ]);
            }

            // Eliminar ingredientes existentes
            $composting->ingredients()->delete();

            // Crear los nuevos ingredientes y restar del inventario
            foreach ($request->ingredients as $ingredientData) {
                // Crear el ingrediente
                Ingredient::create([
                    'composting_id' => $composting->id,
                    'organic_id' => $ingredientData['organic_id'],
                    'amount' => $ingredientData['amount'],
                    'notes' => $ingredientData['notes'] ?? null
                ]);

                // Restar del inventario de bodega
                $organic = Organic::find($ingredientData['organic_id']);
                if ($organic) {
                    WarehouseClassification::create([
                        'date' => $request->start_date,
                        'type' => $organic->type,
                        'movement_type' => 'exit',
                        'weight' => $ingredientData['amount'],
                        'notes' => "Uso en pila de compostaje #{$composting->formatted_pile_num}",
                        'processed_by' => auth()->user()->name
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.composting.index')
                ->with('success', 'Pila de compostaje actualizada exitosamente!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la pila: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Composting $composting)
    {
        DB::beginTransaction();
        try {
            // Delete image if exists
            if ($composting->image && Storage::disk('public')->exists($composting->image)) {
                Storage::disk('public')->delete($composting->image);
            }
            
            // Eliminar la pila (esto también eliminará los ingredientes por cascada)
            // NO devolver al inventario porque los residuos ya fueron procesados en el compostaje
            $composting->delete();
            
            DB::commit();
            
            return redirect()->route('admin.composting.index')
                ->with('success', 'Pila de compostaje eliminada exitosamente!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al eliminar la pila: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF for all compostings
     */
    public function downloadAllCompostingsPDF()
    {
        $compostings = Composting::with(['ingredients.organic', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $pdf = PDF::loadView('admin.composting.pdf.all-compostings', compact('compostings'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('todas_las_pilas_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate PDF for individual composting
     */
    public function downloadCompostingPDF(Composting $composting)
    {
        $composting->load(['ingredients.organic', 'creator', 'trackings']);
        
        // Convertir imagen a base64 si existe
        $imageBase64 = null;
        if ($composting->image && Storage::disk('public')->exists($composting->image)) {
            $imagePath = Storage::disk('public')->path($composting->image);
            $imageData = file_get_contents($imagePath);
            $imageInfo = getimagesize($imagePath);
            $mimeType = $imageInfo['mime'];
            $imageBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        }
        
        $pdf = PDF::loadView('admin.composting.pdf.composting-details', compact('composting', 'imageBase64'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('pila_' . str_replace(' ', '_', $composting->formatted_pile_num) . '_' . date('Y-m-d') . '.pdf');
    }
}
