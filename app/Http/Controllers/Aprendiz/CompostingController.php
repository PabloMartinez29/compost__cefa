<?php

namespace App\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use App\Models\Composting;
use App\Models\Organic;
use App\Models\Ingredient;
use App\Models\WarehouseClassification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class CompostingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compostings = Composting::with(['ingredients.organic', 'creator', 'trackings'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calcular estadísticas usando el mismo criterio de estado que en el modelo (accessor status)
        $totalPiles = $compostings->count();
        $completedPiles = $compostings->filter(function ($composting) {
            return $composting->status === 'Completada';
        })->count();
        $activePiles = $totalPiles - $completedPiles;
        $totalIngredients = $compostings->sum(function ($composting) {
            return $composting->ingredients->count();
        });

        // IDs de pilas con aprobación vigente para eliminar
        $userId = auth()->check() ? auth()->id() : null;
        $approvedCompostingIds = \App\Models\Notification::where('user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'approved')
            ->whereNotNull('composting_id')
            ->pluck('composting_id')
            ->toArray();

        // IDs de pilas con solicitud pendiente
        $pendingCompostingIds = \App\Models\Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'pending')
            ->whereNotNull('composting_id')
            ->pluck('composting_id')
            ->toArray();

        // IDs de pilas con solicitud rechazada
        $rejectedCompostingIds = \App\Models\Notification::where('user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->whereNotNull('composting_id')
            ->pluck('composting_id')
            ->toArray();
        
        // También verificar notificaciones pendientes que fueron rechazadas
        $rejectedFromPending = \App\Models\Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->whereNotNull('composting_id')
            ->pluck('composting_id')
            ->toArray();
        
        $rejectedCompostingIds = array_unique(array_merge($rejectedCompostingIds, $rejectedFromPending));

        return view('aprendiz.composting.index', compact(
            'compostings', 
            'totalPiles', 
            'activePiles', 
            'completedPiles', 
            'totalIngredients',
            'approvedCompostingIds',
            'pendingCompostingIds',
            'rejectedCompostingIds'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Un solo ítem por tipo de residuo, con el total disponible en bodega (no duplicados por registro)
        $types = ['Kitchen', 'Beds', 'Leaves', 'CowDung', 'ChickenManure', 'PigManure', 'Other'];
        $availableOrganics = collect();
        foreach ($types as $type) {
            $availableQuantity = WarehouseClassification::getCurrentInventory($type);
            if ($availableQuantity <= 0) {
                continue;
            }
            $organic = Organic::where('type', $type)->first();
            if (!$organic) {
                continue;
            }
            $availableOrganics->push([
                'id' => $organic->id,
                'type' => $type,
                'type_in_spanish' => $organic->type_in_spanish,
                'available_quantity' => round($availableQuantity, 2),
                'available_quantity_formatted' => number_format($availableQuantity, 2, '.', ''),
            ]);
        }

        return view('aprendiz.composting.create', compact('availableOrganics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'pile_num' => 'required|integer|min:1|unique:compostings,pile_num',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'total_kg' => 'nullable|numeric|min:0.01',
            'efficiency' => 'nullable|numeric|min:0|max:100',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.organic_id' => 'required|exists:organics,id',
            'ingredients.*.amount' => 'required|numeric|min:0.01',
            'ingredients.*.notes' => 'nullable|string|max:500'
        ];
        
        // Validar imagen solo si está presente
        if ($request->hasFile('image')) {
            $rules['image'] = 'image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        }
        
        $request->validate($rules);

        // Validar que no se exceda la cantidad disponible en bodega
        $inventory = WarehouseClassification::getInventoryByType();
        foreach ($request->ingredients as $ingredient) {
            $organic = Organic::find($ingredient['organic_id']);
            $availableQuantity = $inventory[$organic->type] ?? 0;
            
            if ($ingredient['amount'] > $availableQuantity) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "La cantidad solicitada para {$organic->type_in_spanish} excede la cantidad disponible en bodega ({$availableQuantity} Kg)");
            }
        }

        DB::beginTransaction();
        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $imagePath = $file->storeAs('compostings', $name, 'public');
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
            
            // Recargar el modelo para asegurar que la imagen esté disponible
            $composting->refresh();
            
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
                    // Validar que hay suficiente inventario disponible
                    $availableInventory = WarehouseClassification::getAvailableInventory($organic->type);
                    $typeInSpanish = $organic->type_in_spanish;
                    if ($ingredientData['amount'] > $availableInventory) {
                        DB::rollback();
                        return redirect()->back()
                            ->withInput()
                            ->with('error', "No hay suficiente inventario disponible para {$typeInSpanish}. Inventario disponible: " . number_format($availableInventory, 2) . " kg. Intenta usar: " . number_format($ingredientData['amount'], 2) . " kg.");
                    }
                    
                    \Log::info('Creating warehouse exit record for composting', [
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
                    
                    \Log::info('Warehouse record created successfully', [
                        'warehouse_id' => $warehouseRecord->id,
                        'type' => $warehouseRecord->type,
                        'movement_type' => $warehouseRecord->movement_type,
                        'weight' => $warehouseRecord->weight
                    ]);
                } else {
                    \Log::warning('Organic not found for ingredient', [
                        'organic_id' => $ingredientData['organic_id']
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('aprendiz.composting.index')
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
        \Log::info('Show method called for composting ID: ' . $composting->id);
        $composting->load(['ingredients.organic']);
        
        // Si es una petición AJAX o se solicita JSON, devolver JSON
        if (request()->ajax() || request()->wantsJson()) {
            \Log::info('JSON request received for composting ID: ' . $composting->id);
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
                'ingredients' => $composting->ingredients->map(function($ingredient) {
                    return [
                        'id' => $ingredient->id,
                        'ingredient_name' => $ingredient->ingredient_name,
                        'amount' => $ingredient->amount,
                        'formatted_amount' => $ingredient->formatted_amount,
                        'notes' => $ingredient->notes
                    ];
                })
            ]);
        }
        
        \Log::info('Regular request for composting ID: ' . $composting->id);
        return view('aprendiz.composting.show', compact('composting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Composting $composting)
    {
        // Obtener inventario actual de bodega por tipo
        $inventory = WarehouseClassification::getInventoryByType();
        
        // Filtrar solo los tipos que tienen cantidad disponible
        $availableTypes = array_filter($inventory, function($quantity) {
            return $quantity > 0;
        });
        
        // Obtener residuos orgánicos que tienen cantidad en bodega (todos los usuarios)
        $availableOrganics = Organic::whereIn('type', array_keys($availableTypes))
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($organic) use ($inventory) {
                // Agregar información de cantidad disponible y datos formateados
                $organic->available_quantity = $inventory[$organic->type] ?? 0;
                $organic->type_in_spanish = $organic->getTypeInSpanishAttribute();
                $organic->formatted_weight = $organic->getFormattedWeightAttribute();
                return $organic;
            })
            ->toArray();

        $composting->load('ingredients.organic');

        // Preparar ingredientes existentes para la vista
        $existingIngredients = $composting->ingredients->map(function($ingredient) {
            return [
                'id' => $ingredient->id,
                'organic_id' => $ingredient->organic_id,
                'amount' => $ingredient->amount,
                'notes' => $ingredient->notes,
                'organic' => $ingredient->organic ? [
                    'id' => $ingredient->organic->id,
                    'type_in_spanish' => $ingredient->organic->type_in_spanish
                ] : null
            ];
        })->toArray();

        return view('aprendiz.composting.edit', compact('composting', 'availableOrganics', 'existingIngredients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Composting $composting)
    {
        $rules = [
            'pile_num' => 'required|integer|min:1|unique:compostings,pile_num,' . $composting->id,
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'total_kg' => 'nullable|numeric|min:0.01',
            'efficiency' => 'nullable|numeric|min:0|max:100',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.organic_id' => 'required|exists:organics,id',
            'ingredients.*.amount' => 'required|numeric|min:0.01',
            'ingredients.*.notes' => 'nullable|string|max:500'
        ];
        
        // Nota: Los ingredientes se validan pero no se modifican, solo se usan para validar el formulario
        
        // Validar imagen solo si está presente
        if ($request->hasFile('image')) {
            $rules['image'] = 'image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        }
        
        $request->validate($rules);

        DB::beginTransaction();
        try {
            // Handle image upload
            $imagePath = $composting->image; // Mantener la imagen actual por defecto
            
            if ($request->has('remove_image') && $request->remove_image == '1') {
                if ($composting->image && Storage::disk('public')->exists($composting->image)) {
                    Storage::disk('public')->delete($composting->image);
                }
                $imagePath = null;
            }
            
            if ($request->hasFile('image')) {
                if ($composting->image && Storage::disk('public')->exists($composting->image)) {
                    Storage::disk('public')->delete($composting->image);
                }
                $file = $request->file('image');
                $name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $imagePath = $file->storeAs('compostings', $name, 'public');
            }
            
            // Actualizar el compostaje (solo los campos permitidos, los ingredientes no se tocan)
            $composting->update([
                'pile_num' => $request->pile_num,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_kg' => $request->total_kg,
                'efficiency' => $request->efficiency,
                'image' => $imagePath
            ]);

            // Los ingredientes no se modifican en edición - se mantienen los existentes
            // No es necesario validar ni modificar los ingredientes, simplemente se ignoran

            DB::commit();

            return redirect()->route('aprendiz.composting.index')
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
        $currentUserId = auth()->check() ? auth()->id() : null;
        
        // Verificar que el registro pertenece al usuario O fue creado por un admin
        $composting->load('creator');
        $isOwner = $composting->created_by === $currentUserId;
        $isCreatedByAdmin = $composting->creator && $composting->creator->role === 'admin';
        
        if (!$isOwner && !$isCreatedByAdmin) {
            return redirect()->route('aprendiz.composting.index')
                ->with('permission_required', 'No tiene permisos para eliminar este registro.');
        }
        
        // Verificar que hay una solicitud aprobada
        $approvedNotification = \App\Models\Notification::where('user_id', $currentUserId)
            ->where('composting_id', $composting->id)
            ->where('type', 'delete_request')
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->first();
        
        if (!$approvedNotification) {
            return redirect()->route('aprendiz.composting.index')
                ->with('permission_required', 'No tiene permiso para eliminar esta pila. Debe solicitar permiso primero y esperar la aprobación del administrador.');
        }
        
        DB::beginTransaction();
        try {
            if ($composting->image && Storage::disk('public')->exists($composting->image)) {
                Storage::disk('public')->delete($composting->image);
            }
            
            // Eliminar la pila (esto también eliminará los ingredientes por cascada)
            // NO devolver al inventario porque los residuos ya fueron procesados en el compostaje
            $composting->delete();
            
            // Marcar la notificación como procesada o eliminarla
            $approvedNotification->update(['read_at' => now()]);
            
            DB::commit();
            
            return redirect()->route('aprendiz.composting.index')
                ->with('success', 'Pila de compostaje eliminada exitosamente!');
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error al eliminar pila de compostaje: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al eliminar la pila. Por favor, intente nuevamente.');
        }
    }

    /**
     * Solicitar permiso para editar un registro
     */
    public function requestEditPermission(Composting $composting)
    {
        // Verificar que el registro pertenece al usuario
        $currentUserId = auth()->check() ? auth()->id() : null;
        if ($composting->created_by !== $currentUserId) {
            return redirect()->route('aprendiz.composting.index')
                ->with('permission_required', 'No puede solicitar permisos para registros que no le pertenecen.');
        }

        // Aquí se implementaría la lógica para enviar notificación al administrador
        // Por ahora, solo mostramos un mensaje
        return redirect()->route('aprendiz.composting.index')
            ->with('success', 'Solicitud de edición enviada al administrador. Recibirá una notificación cuando sea aprobada.');
    }

    /**
     * Solicitar permiso para eliminar un registro
     */
    public function requestDeletePermission(Composting $composting)
    {
        \Log::info('=== REQUEST DELETE PERMISSION START ===');
        \Log::info('Composting ID: ' . $composting->id);
        \Log::info('Request method: ' . request()->method());
        \Log::info('Request URL: ' . request()->url());
        
        $currentUserId = auth()->check() ? auth()->id() : null;
        \Log::info('Current User ID: ' . $currentUserId);
        \Log::info('Composting created_by: ' . $composting->created_by);
        
        // Verificar que el registro pertenece al usuario O fue creado por un admin
        $composting->load('creator');
        $isOwner = $composting->created_by === $currentUserId;
        $isCreatedByAdmin = $composting->creator && $composting->creator->role === 'admin';
        
        if (!$isOwner && !$isCreatedByAdmin) {
            \Log::info('User is not the creator and creator is not admin, redirecting with permission_required message');
            return redirect()->route('aprendiz.composting.index')
                ->with('permission_required', 'No puede solicitar permisos para registros que no le pertenecen.');
        }

        // Evitar solicitudes duplicadas si ya hay una pendiente o aprobada
        $currentUserId = auth()->check() ? auth()->id() : null;
        $existing = \App\Models\Notification::where('from_user_id', $currentUserId)
            ->where('composting_id', $composting->id)
            ->where('type', 'delete_request')
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
        if ($existing && $existing->status === 'pending') {
            return redirect()->route('aprendiz.composting.index')
                ->with('permission_required', 'Su solicitud de eliminación ya está pendiente de aprobación del administrador.');
        }
        
        if ($existing && $existing->status === 'approved') {
            return redirect()->route('aprendiz.composting.index')
                ->with('success', 'Su solicitud ya fue aprobada. Ahora puede eliminar el registro.');
        }

        // Si hay una solicitud rechazada, eliminarla para permitir nueva solicitud
        $rejected = \App\Models\Notification::where('from_user_id', $currentUserId)
            ->where('composting_id', $composting->id)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->first();
        
        if ($rejected) {
            $rejected->delete();
        }

        // Buscar todos los administradores y crear notificaciones para cada uno
        $admins = \App\Models\User::where('role', 'admin')->get();
        
        if ($admins->count() > 0) {
            foreach ($admins as $admin) {
                // Crear notificación para cada administrador
                $notification = \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'from_user_id' => $currentUserId,
                    'composting_id' => $composting->id,
                    'type' => 'delete_request',
                    'status' => 'pending',
                    'message' => (auth()->check() ? auth()->user()->name : 'Usuario') . ' solicita permiso para eliminar la pila de compostaje #' . $composting->formatted_pile_num
                ]);
                
                \Log::info('Notification created with ID: ' . $notification->id . ' for composting ID: ' . $composting->id . ' for admin ID: ' . $admin->id);
            }
        } else {
            \Log::warning('No se encontraron administradores para enviar notificación de eliminación de pila de compostaje');
        }

        \Log::info('=== REQUEST DELETE PERMISSION END ===');
        return redirect()->route('aprendiz.composting.index')
            ->with('success', 'Solicitud de eliminación enviada al administrador. Recibirá una notificación cuando sea aprobada.');
    }

    /**
     * Verificar el estado de una solicitud de eliminación
     */
    public function checkDeletePermissionStatus(Composting $composting)
    {
        $currentUserId = auth()->check() ? auth()->id() : null;
        
        \Log::info('Checking delete permission status for composting ID: ' . $composting->id . ', User ID: ' . $currentUserId);
        
        // Primero buscar si hay una notificación de respuesta del admin (aprobada o rechazada)
        // Esta es la notificación donde el aprendiz es el user_id (quien recibe la respuesta)
        $responseNotification = \App\Models\Notification::where('user_id', $currentUserId)
            ->where('composting_id', $composting->id)
            ->where('type', 'delete_request')
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($responseNotification) {
            \Log::info('Response notification found with status: ' . $responseNotification->status);
            return response()->json([
                'has_request' => true,
                'status' => $responseNotification->status,
                'message' => $responseNotification->status === 'approved' 
                    ? 'Su solicitud ya fue aprobada. Ahora puede eliminar el registro.'
                    : 'Su solicitud fue rechazada.'
            ]);
        }
        
        // Si no hay respuesta, buscar si hay una solicitud pendiente (donde el aprendiz es from_user_id)
        $pendingRequest = \App\Models\Notification::where('from_user_id', $currentUserId)
            ->where('composting_id', $composting->id)
            ->where('type', 'delete_request')
            ->where('status', 'pending')
            ->first();
        
        if ($pendingRequest) {
            \Log::info('Pending request found');
            return response()->json([
                'has_request' => true,
                'status' => 'pending',
                'message' => 'Su solicitud de eliminación está pendiente de aprobación del administrador.'
            ]);
        }
        
        \Log::info('No notification found for composting ID: ' . $composting->id . ' and user ID: ' . $currentUserId);
        return response()->json([
            'has_request' => false,
            'message' => 'No hay solicitudes pendientes para esta pila.'
        ]);
    }

    /**
     * Generate PDF for all compostings (o solo los filtrados si se pasan ids)
     */
    public function downloadAllCompostingsPDF(Request $request)
    {
        $query = Composting::with(['ingredients.organic', 'creator'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('ids')) {
            $ids = array_filter(array_map('intval', explode(',', $request->ids)));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        $compostings = $query->get();

        $pdf = PDF::loadView('aprendiz.composting.pdf.all-compostings', compact('compostings'))
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
        
        $pdf = PDF::loadView('aprendiz.composting.pdf.composting-details', compact('composting', 'imageBase64'))
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
