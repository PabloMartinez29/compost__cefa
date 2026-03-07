<?php

// Controlador Aprendiz OrganicController — Residuos orgánicos (vista aprendiz)
namespace App\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use App\Models\Organic;
use App\Models\WarehouseClassification;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class OrganicController extends Controller
{
    // Listar todos los registros
    public function index()
    {
        $organics = Organic::with('creator')->orderBy('date', 'desc')->get();
        
        // Calcular estadísticas
        $totalWeight = Organic::sum('weight');
        $totalRecords = Organic::count();
        $todayRecords = Organic::whereDate('created_at', today())->count();
        $todayWeight = Organic::whereDate('created_at', today())->sum('weight');

        // Verificar notificaciones recientes
        $userId = auth()->check() ? auth()->id() : null;
        $recentNotifications = Notification::where('user_id', $userId)
            ->whereIn('status', ['approved', 'rejected'])
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();

        // Marcar las no leídas como leídas al mostrarlas
        foreach ($recentNotifications as $notification) {
            $notification->update(['read_at' => now()]);
        }

        // IDs de orgánicos con aprobación vigente para eliminar
        $approvedOrganicIds = Notification::where('user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'approved')
            ->whereNotNull('organic_id')
            ->pluck('organic_id')
            ->toArray();

        // IDs de orgánicos con solicitud pendiente
        $pendingOrganicIds = Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'pending')
            ->whereNotNull('organic_id')
            ->pluck('organic_id')
            ->toArray();

        // IDs de orgánicos con solicitud rechazada
        $rejectedOrganicIds = Notification::where('user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->whereNotNull('organic_id')
            ->pluck('organic_id')
            ->toArray();
        
        // También verificar notificaciones pendientes que fueron rechazadas
        $rejectedFromPending = Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->whereNotNull('organic_id')
            ->pluck('organic_id')
            ->toArray();
        
        $rejectedOrganicIds = array_unique(array_merge($rejectedOrganicIds, $rejectedFromPending));
        
        // Mostrar vista
        return view('aprendiz.organic.index', compact(
            'organics',
            'totalWeight',
            'totalRecords',
            'todayRecords',
            'todayWeight',
            'recentNotifications',
            'approvedOrganicIds',
            'pendingOrganicIds',
            'rejectedOrganicIds'
        ));
    }

    // Mostrar formulario de creación
    public function create()
    {
        // Mostrar vista
        return view('aprendiz.organic.create');
    }

    // Guardar nuevo registro
    public function store(Request $request)
    {
        // Validar datos recibidos
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:Kitchen,Beds,Leaves,CowDung,ChickenManure,PigManure,Other',
            'weight' => 'required|numeric|min:0.01',
            'delivered_by' => 'required|string|max:100',
            'received_by' => 'required|string|max:100',
            'notes' => 'nullable|string',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'img.required' => 'La imagen es obligatoria.',
        ]);

        $data = $request->all();
        
        // Handle image upload (public/storage/organics para que en el servidor no afecte)
        // Procesar archivo
        if ($request->hasFile('img')) {
            $archivo = $request->file('img');
            $nombre = time() . '_' . $archivo->getClientOriginalName();
            $dir = upload_base_path('storage/organics');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $archivo->move($dir, $nombre);
            $data['img'] = 'organics/' . $nombre;
        }

        // Agregar el ID del usuario que crea el registro
        $data['created_by'] = auth()->check() ? auth()->id() : null;

        $organic = Organic::create($data);

        // Crear movimiento automático en bodega de clasificación (vinculado al residuo para poder actualizar al editar)
        WarehouseClassification::create([
            'organic_id' => $organic->id,
            'date' => $data['date'],
            'type' => $data['type'],
            'movement_type' => 'entry', // Entrada automática
            'weight' => $data['weight'],
            'notes' => 'Entrada automática desde registro de residuos orgánicos',
            'processed_by' => $data['received_by'],
            'img' => $data['img'] // Misma imagen si existe
        ]);

        // Redirigir con mensaje
            return redirect()->route('aprendiz.organic.index')->with('success', 'Residuo orgánico registrado y clasificado exitosamente!');
    }

    // Mostrar detalle del registro
    public function show(Organic $organic)
    {
        // Si es una petición AJAX, devolver JSON
        if (request()->ajax()) {
            try {
                // Cargar la relación del creador
                $organic->load('creator');

                $imgUrl = null;
                if ($organic->img) {
                    $uploadPath = function_exists('upload_base_path')
                        ? upload_base_path('storage/' . $organic->img)
                        : public_path('storage/' . $organic->img);
                    if (file_exists($uploadPath)) {
                        $imgUrl = asset('storage/' . $organic->img);
                    }
                }

                return response()->json([
                    'id' => $organic->id,
                    'date' => $organic->date ? $organic->date->format('Y-m-d') : null,
                    'date_formatted' => $organic->date ? $organic->formatted_date : '',
                    'type' => $organic->type,
                    'type_in_spanish' => $organic->type_in_spanish,
                    'weight' => (float) $organic->weight,
                    'formatted_weight' => $organic->formatted_weight,
                    'delivered_by' => $organic->delivered_by ?? '',
                    'received_by' => $organic->received_by ?? '',
                    'notes' => $organic->notes ?? '',
                    'img' => $organic->img,
                    'img_url' => $imgUrl,
                    'created_at' => $organic->created_at ? $organic->created_at->format('Y-m-d H:i:s') : null,
                    'created_at_formatted' => $organic->created_at ? $organic->created_at->format('d/m/Y H:i:s') : '',
                    'created_by_info' => $organic->created_by_info ?? '',
                    'updated_at' => $organic->updated_at ? $organic->updated_at->format('Y-m-d H:i:s') : null,
                ]);
            } catch (\Throwable $e) {
                return response()->json(['error' => 'Error al cargar el registro.', 'message' => $e->getMessage()], 500);
            }
        }
        
        // Mostrar vista
        return view('aprendiz.organic.show', compact('organic'));
    }

    // Mostrar formulario de edición
    public function edit(Organic $organic)
    {
        // Mostrar vista
        return view('aprendiz.organic.edit', compact('organic'));
    }

    // Actualizar registro existente
    public function update(Request $request, Organic $organic)
    {
        // Validar datos recibidos
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:Kitchen,Beds,Leaves,CowDung,ChickenManure,PigManure,Other',
            'weight' => 'required|numeric|min:0.01',
            'delivered_by' => 'required|string|max:100',
            'received_by' => 'required|string|max:100',
            'notes' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['date', 'type', 'weight', 'delivered_by', 'received_by', 'notes']);

        // Guardar valores anteriores para localizar la entrada en bodega (registros sin organic_id)
        $oldWeight = $organic->weight;
        $oldDate = $organic->date?->format('Y-m-d');
        $oldType = $organic->type;

        // Imagen: solo actualizar si se sube una nueva; si no, conservar la actual
        // Procesar archivo
        if ($request->hasFile('img')) {
            if ($organic->img && file_exists(upload_base_path('storage/' . $organic->img))) {
                unlink(upload_base_path('storage/' . $organic->img));
            }
            $archivo = $request->file('img');
            $nombre = time() . '_' . $archivo->getClientOriginalName();
            $dir = upload_base_path('storage/organics');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $archivo->move($dir, $nombre);
            $data['img'] = 'organics/' . $nombre;
        } else {
            $data['img'] = $organic->img;
        }

        $organic->update($data);

        // Sincronizar bodega: actualizar la entrada asociada a este residuo para que el inventario refleje el nuevo peso
        $warehouseEntry = WarehouseClassification::where('organic_id', $organic->id)
            ->where('movement_type', 'entry')
            ->first();

        if (!$warehouseEntry && $oldDate !== null) {
            // Registros antiguos sin organic_id: buscar por fecha, tipo y peso anterior
            $warehouseEntry = WarehouseClassification::where('movement_type', 'entry')
                ->where('type', $oldType)
                ->where('date', $oldDate)
                ->where('weight', $oldWeight)
                ->where('notes', 'like', 'Entrada automática desde registro%')
                ->first();
            if ($warehouseEntry) {
                $warehouseEntry->update(['organic_id' => $organic->id]);
            }
        }

        if ($warehouseEntry) {
            $warehouseEntry->update([
                'weight' => $organic->weight,
                'date' => $organic->date,
                'type' => $organic->type,
            ]);
        }

        // Redirigir con mensaje
            return redirect()->route('aprendiz.organic.index')->with('success', 'Residuo orgánico actualizado exitosamente! El inventario de bodega se ha actualizado.');
    }

    // Eliminar registro del sistema
    public function destroy(Organic $organic)
    {
        $currentUserId = auth()->check() ? auth()->id() : null;
        
        // Verificar que el registro pertenece al usuario
        if ($organic->created_by !== $currentUserId) {
            // Redirigir con mensaje
            return redirect()->route('aprendiz.organic.index')
                ->with('permission_required', 'No tiene permisos para eliminar este registro.');
        }
        
        // Verificar que hay una solicitud aprobada
        $approvedNotification = Notification::where('user_id', $currentUserId)
            ->where('organic_id', $organic->id)
            ->where('type', 'delete_request')
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->first();
        
        if (!$approvedNotification) {
            // Redirigir con mensaje
            return redirect()->route('aprendiz.organic.index')
                ->with('permission_required', 'No tiene permiso para eliminar este registro. Debe solicitar permiso primero y esperar la aprobación del administrador.');
        }
        
        // Validar que hay suficiente inventario disponible antes de eliminar
        $availableInventory = WarehouseClassification::getAvailableInventory($organic->type);
        if ($organic->weight > $availableInventory) {
            // Redirigir con mensaje
            return redirect()->route('aprendiz.organic.index')
                ->with('error', "No se puede eliminar este registro. El peso ({$organic->weight} kg) excede el inventario disponible (" . number_format($availableInventory, 2) . " kg) para este tipo de residuo.");
        }
        
        // Restar del inventario de bodega antes de eliminar
        WarehouseClassification::create([
            'date' => now()->toDateString(),
            'type' => $organic->type,
            'movement_type' => 'exit',
            'weight' => $organic->weight,
            'notes' => "Eliminación de residuo orgánico #" . str_pad($organic->id, 3, '0', STR_PAD_LEFT),
            'processed_by' => auth()->user()->name
        ]);

        // Delete image if exists
        if ($organic->img && file_exists(upload_base_path('storage/' . $organic->img))) {
            unlink(upload_base_path('storage/' . $organic->img));
        }

        // Marcar la notificación como procesada
        $approvedNotification->update(['read_at' => now()]);

        $organic->delete();

        // Redirigir con mensaje
            return redirect()->route('aprendiz.organic.index')->with('success', 'Residuo orgánico eliminado exitosamente! Las cantidades han sido restadas del inventario.');
    }

    // Solicitar permiso para editar un registro
    public function requestEditPermission(Organic $organic)
    {
        // Verificar que el registro pertenece al usuario
        $currentUserId = auth()->check() ? auth()->id() : null;
        if ($organic->created_by !== $currentUserId) {
            // Redirigir con mensaje
            return redirect()->route('aprendiz.organic.index')
                ->with('permission_required', 'No puede solicitar permisos para registros que no le pertenecen.');
        }

        // Aquí se implementaría la lógica para enviar notificación al administrador
        // Por ahora, solo mostramos un mensaje
        // Redirigir con mensaje
            return redirect()->route('aprendiz.organic.index')
            ->with('success', 'Solicitud de edición enviada al administrador. Recibirá una notificación cuando sea aprobada.');
    }

    // Solicitar permiso para eliminar un registro
    public function requestDeletePermission(Organic $organic)
    {
        // Verificar que el registro pertenece al usuario
        $currentUserId = auth()->check() ? auth()->id() : null;
        if ($organic->created_by !== $currentUserId) {
            // Redirigir con mensaje
            return redirect()->route('aprendiz.organic.index')
                ->with('permission_required', 'No puede solicitar permisos para registros que no le pertenecen.');
        }

        // Evitar solicitudes duplicadas si ya hay una pendiente o aprobada
        $currentUserId = auth()->check() ? auth()->id() : null;
        $existing = Notification::where('from_user_id', $currentUserId)
            ->where('organic_id', $organic->id)
            ->where('type', 'delete_request')
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
        if ($existing && $existing->status === 'pending') {
            // Redirigir con mensaje
            return redirect()->route('aprendiz.organic.index')
                ->with('permission_required', 'Su solicitud de eliminación ya está pendiente de aprobación del administrador.');
        }
        
        if ($existing && $existing->status === 'approved') {
            // Redirigir con mensaje
            return redirect()->route('aprendiz.organic.index')
                ->with('success', 'Su solicitud ya fue aprobada. Ahora puede eliminar el registro.');
        }

        // Si hay una solicitud rechazada, eliminarla para permitir nueva solicitud
        $rejected = Notification::where('from_user_id', $currentUserId)
            ->where('organic_id', $organic->id)
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
                Notification::create([
                    'user_id' => $admin->id,
                    'from_user_id' => $currentUserId,
                    'organic_id' => $organic->id,
                    'type' => 'delete_request',
                    'status' => 'pending',
                    'message' => (auth()->check() ? auth()->user()->name : 'Usuario') . ' solicita permiso para eliminar el registro #' . str_pad($organic->id, 3, '0', STR_PAD_LEFT)
                ]);
            }
        } else {
            \Log::warning('No se encontraron administradores para enviar notificación de eliminación de residuo orgánico');
        }

        // Redirigir con mensaje
            return redirect()->route('aprendiz.organic.index')
            ->with('success', 'Solicitud de eliminación enviada al administrador.');
    }

    // Generate PDF for all organics (o solo
    public function downloadAllOrganicsPDF(Request $request)
    {
        $query = Organic::with('creator')->orderBy('date', 'desc');

        if ($request->filled('ids')) {
            $ids = array_filter(array_map('intval', explode(',', $request->ids)));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        $organics = $query->get();

        $pdf = PDF::loadView('admin.organic.pdf.all-organics', compact('organics'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('todos_los_residuos_' . date('Y-m-d') . '.pdf');
    }

    // Generate PDF for individual organic
    public function downloadOrganicPDF(Organic $organic)
    {
        $organic->load('creator');
        
        // Convertir imagen a base64 si existe
        $imageBase64 = null;
        if ($organic->img && file_exists(upload_base_path('storage/' . $organic->img))) {
            $imagePath = upload_base_path('storage/' . $organic->img);
            $imageData = file_get_contents($imagePath);
            $imageInfo = getimagesize($imagePath);
            $mimeType = $imageInfo['mime'];
            $imageBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        }
        
        $pdf = PDF::loadView('admin.organic.pdf.organic-details', compact('organic', 'imageBase64'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('residuo_' . str_pad($organic->id, 3, '0', STR_PAD_LEFT) . '_' . date('Y-m-d') . '.pdf');
    }
}
