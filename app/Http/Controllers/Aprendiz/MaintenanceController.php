<?php

// Controlador Aprendiz MaintenanceController — Mantenimientos (vista aprendiz)
namespace App\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Machinery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class MaintenanceController extends Controller
{
    // Listar todos los registros
    public function index()
    {
        $maintenances = Maintenance::with('machinery')
            ->orderBy('date', 'desc')
            ->get();
        
        // Statistics
        $totalMaintenances = Maintenance::count();
        $todayMaintenances = Maintenance::whereDate('created_at', today())->count();
        $thisMonthMaintenances = Maintenance::whereMonth('date', now()->month)
                                            ->whereYear('date', now()->year)
                                            ->count();
        $maintenanceCount = Maintenance::where('type', 'M')->count();
        $operationsCount = Maintenance::where('type', 'O')->count();
        
        // IDs de mantenimientos con aprobación vigente para eliminar
        $userId = auth()->check() ? auth()->id() : null;
        $approvedMaintenanceIds = \App\Models\Notification::where('user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'approved')
            ->whereNotNull('maintenance_id')
            ->pluck('maintenance_id')
            ->toArray();

        // IDs de mantenimientos con solicitud pendiente
        $pendingMaintenanceIds = \App\Models\Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'pending')
            ->whereNotNull('maintenance_id')
            ->pluck('maintenance_id')
            ->toArray();

        // IDs de mantenimientos con solicitud rechazada
        $rejectedMaintenanceIds = \App\Models\Notification::where('user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->whereNotNull('maintenance_id')
            ->pluck('maintenance_id')
            ->toArray();
        
        // También verificar notificaciones pendientes que fueron rechazadas
        $rejectedFromPending = \App\Models\Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->whereNotNull('maintenance_id')
            ->pluck('maintenance_id')
            ->toArray();
        
        $rejectedMaintenanceIds = array_unique(array_merge($rejectedMaintenanceIds, $rejectedFromPending));
        $machineries = Machinery::orderBy('name')->get();

        // Mostrar vista
        return view('aprendiz.machinery.maintenances.index', compact(
            'maintenances', 
            'totalMaintenances', 
            'todayMaintenances', 
            'thisMonthMaintenances',
            'maintenanceCount',
            'operationsCount',
            'approvedMaintenanceIds',
            'pendingMaintenanceIds',
            'rejectedMaintenanceIds',
            'machineries'
        ));
    }

    // Mostrar formulario de creación
    public function create()
    {
        // Mostrar todas las maquinarias, incluso las que ya tienen registros
        $machineries = Machinery::orderBy('name')->get();
        // Mostrar vista
        return view('aprendiz.machinery.maintenances.create', compact('machineries'));
    }

    // Guardar nuevo registro
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'machinery_id' => 'required|exists:machineries,id',
            'date' => 'required|date|before_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:date',
            'type' => 'required|in:O,M',
            'description' => 'required|string|max:1000',
            'responsible' => 'required|string|max:150',
        ], [
            'machinery_id.required' => 'Debe seleccionar una maquinaria.',
            'machinery_id.exists' => 'La maquinaria seleccionada no existe.',
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'La fecha debe ser válida.',
            'date.before_or_equal' => 'La fecha no puede ser futura.',
            'end_date.date' => 'La fecha de fin debe ser válida.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'type.required' => 'Debe seleccionar el tipo de registro.',
            'type.in' => 'El tipo de registro no es válido.',
            'description.required' => 'La descripción es obligatoria.',
            'description.max' => 'La descripción no debe exceder 1000 caracteres.',
            'responsible.required' => 'El responsable es obligatorio.',
            'responsible.max' => 'El nombre del responsable no debe exceder 150 caracteres.',
        ]);

        if ($validator->fails()) {
            // Redirigir con mensaje
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            
            // Si se registra una fecha de fin de mantenimiento, cambiar el tipo a "O" (Operación)
            if ($request->has('end_date') && $request->end_date && $request->type == 'M') {
                $data['type'] = 'O';
            }
            
            $data['created_by'] = auth()->id();
            Maintenance::create($data);

            // Redirigir con mensaje
            return redirect()->route('aprendiz.machinery.maintenance.index')
                ->with('success', 'Registro de mantenimiento creado exitosamente.');
        } catch (\Exception $e) {
            // Redirigir con mensaje
            return redirect()->back()
                ->with('error', 'Error al crear el registro: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Mostrar detalle del registro
    public function show(Maintenance $maintenance)
    {
        $maintenance->load('machinery');
        
        // Si es una petición AJAX, devolver JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'id' => $maintenance->id,
                'machinery_name' => $maintenance->machinery->name ?? 'N/A',
                'machinery_brand' => $maintenance->machinery->brand ?? 'N/A',
                'machinery_model' => $maintenance->machinery->model ?? 'N/A',
                'date' => $maintenance->date->format('Y-m-d'),
                'date_formatted' => $maintenance->date->format('d/m/Y'),
                'type' => $maintenance->type,
                'type_name' => $maintenance->type_name,
                'description' => $maintenance->description,
                'responsible' => $maintenance->responsible,
                'created_at' => $maintenance->created_at->format('d/m/Y H:i:s'),
                'created_at_formatted' => $maintenance->created_at->format('d/m/Y H:i:s'),
            ]);
        }
        
        // Mostrar vista
        return view('aprendiz.machinery.maintenances.show', compact('maintenance'));
    }

    // Mostrar formulario de edición
    public function edit(Maintenance $maintenance)
    {
        $machineries = Machinery::orderBy('name')->get();
        
        // Si es una petición AJAX, devolver JSON con maquinarias
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'maintenance' => [
                    'id' => $maintenance->id,
                    'machinery_id' => $maintenance->machinery_id,
                    'date' => $maintenance->date->format('Y-m-d'),
                    'end_date' => $maintenance->end_date ? $maintenance->end_date->format('Y-m-d') : null,
                    'type' => $maintenance->type,
                    'responsible' => $maintenance->responsible,
                    'description' => $maintenance->description,
                ],
                'machineries' => $machineries->map(function($machinery) {
                    return [
                        'id' => $machinery->id,
                        'name' => $machinery->name,
                        'brand' => $machinery->brand,
                        'model' => $machinery->model,
                    ];
                })
            ]);
        }
        
        // Mostrar vista
        return view('aprendiz.machinery.maintenances.edit', compact('maintenance', 'machineries'));
    }

    // Actualizar registro existente
    public function update(Request $request, Maintenance $maintenance)
    {
        $validator = Validator::make($request->all(), [
            'machinery_id' => 'required|exists:machineries,id',
            'date' => 'required|date|before_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:date',
            'type' => 'required|in:O,M',
            'description' => 'required|string|max:1000',
            'responsible' => 'required|string|max:150',
        ], [
            'machinery_id.required' => 'Debe seleccionar una maquinaria.',
            'machinery_id.exists' => 'La maquinaria seleccionada no existe.',
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'La fecha debe ser válida.',
            'date.before_or_equal' => 'La fecha no puede ser futura.',
            'end_date.date' => 'La fecha de fin debe ser válida.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'type.required' => 'Debe seleccionar el tipo de registro.',
            'type.in' => 'El tipo de registro no es válido.',
            'description.required' => 'La descripción es obligatoria.',
            'description.max' => 'La descripción no debe exceder 1000 caracteres.',
            'responsible.required' => 'El responsable es obligatorio.',
            'responsible.max' => 'El nombre del responsable no debe exceder 150 caracteres.',
        ]);

        if ($validator->fails()) {
            // Redirigir con mensaje
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->only(['machinery_id', 'date', 'end_date', 'type', 'description', 'responsible']);
            
            // Respetar siempre el tipo elegido en el formulario (M o O); no sobrescribir por end_date
            $maintenance->update($data);
            // Si vuelve a Operación, reiniciar el cronómetro de la maquinaria
            if (($data['type'] ?? '') === 'O' && $maintenance->machinery) {
                $maintenance->machinery->scheduleNextMaintenanceDue();
            }
            
            // Redirigir con mensaje
            return redirect()->route('aprendiz.machinery.maintenance.index')
                ->with('success', 'Registro de mantenimiento actualizado exitosamente.');
        } catch (\Exception $e) {
            // Redirigir con mensaje
            return redirect()->back()
                ->with('error', 'Error al actualizar el registro: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Eliminar registro del sistema
    // Request permission to delete maintenance
    public function requestDeletePermission(Maintenance $maintenance)
    {
        $currentUserId = auth()->check() ? auth()->id() : null;

        // Un aprendiz no puede solicitar eliminar registros de otro aprendiz
        if ($maintenance->created_by !== $currentUserId) {
            // Redirigir con mensaje
            return redirect()->route('aprendiz.machinery.maintenance.index')
                ->with('permission_required', 'No puede solicitar permisos para registros que no le pertenecen.');
        }

        // Evitar solicitudes duplicadas
        $existing = \App\Models\Notification::where('from_user_id', $currentUserId)
            ->where('maintenance_id', $maintenance->id)
            ->where('type', 'delete_request')
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
        if ($existing && $existing->status === 'pending') {
            // Redirigir con mensaje
            return redirect()->route('aprendiz.machinery.maintenance.index')
                ->with('permission_required', 'Su solicitud de eliminación ya está pendiente de aprobación del administrador.');
        }
        
        if ($existing && $existing->status === 'approved') {
            // Redirigir con mensaje
            return redirect()->route('aprendiz.machinery.maintenance.index')
                ->with('success', 'Su solicitud ya fue aprobada. Ahora puede eliminar el registro.');
        }

        // Si hay una solicitud rechazada, eliminarla
        $rejected = \App\Models\Notification::where('from_user_id', $currentUserId)
            ->where('maintenance_id', $maintenance->id)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->first();
        
        if ($rejected) {
            $rejected->delete();
        }

        // Buscar todos los administradores
        $admins = \App\Models\User::where('role', 'admin')->get();
        
        if ($admins->isNotEmpty()) {
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'from_user_id' => $currentUserId,
                    'maintenance_id' => $maintenance->id,
                    'type' => 'delete_request',
                    'status' => 'pending',
                    'message' => (auth()->check() ? auth()->user()->name : 'Usuario') . ' solicita permiso para eliminar el control de actividades #' . str_pad($maintenance->id, 3, '0', STR_PAD_LEFT)
                ]);
            }
        }

        // Redirigir con mensaje
            return redirect()->route('aprendiz.machinery.maintenance.index')
            ->with('success', 'Solicitud de eliminación enviada al administrador.');
    }

    // Check delete permission status
    public function checkDeletePermissionStatus(Maintenance $maintenance)
    {
        $currentUserId = auth()->check() ? auth()->id() : null;
        
        $responseNotification = \App\Models\Notification::where('user_id', $currentUserId)
            ->where('maintenance_id', $maintenance->id)
            ->where('type', 'delete_request')
            ->whereIn('status', ['approved', 'rejected'])
            ->first();

        if ($responseNotification) {
            return response()->json([
                'has_request' => true,
                'status' => $responseNotification->status,
                'message' => $responseNotification->status === 'approved' 
                    ? 'Su solicitud ya fue aprobada. Ahora puede eliminar el registro.'
                    : 'Su solicitud fue rechazada.'
            ]);
        }

        $pendingRequest = \App\Models\Notification::where('from_user_id', $currentUserId)
            ->where('maintenance_id', $maintenance->id)
            ->where('type', 'delete_request')
            ->where('status', 'pending')
            ->first();
        
        if ($pendingRequest) {
            return response()->json([
                'has_request' => true,
                'status' => $pendingRequest->status,
                'message' => 'Su solicitud de eliminación está pendiente de aprobación del administrador.'
            ]);
        }
        
        return response()->json([
            'has_request' => false,
            'message' => 'No hay solicitudes pendientes para este registro.'
        ]);
    }

    public function destroy(Maintenance $maintenance)
    {
        $currentUserId = auth()->check() ? auth()->id() : null;

        // Un aprendiz no puede eliminar registros de otro aprendiz
        if ($maintenance->created_by !== $currentUserId) {
            // Redirigir con mensaje
            return redirect()->back()
                ->with('error', 'No tiene permisos para eliminar este registro. Solo puede eliminar sus propios registros.');
        }

        // Verificar si hay una notificación de aprobación
        $approvedNotification = \App\Models\Notification::where('user_id', $currentUserId)
            ->where('maintenance_id', $maintenance->id)
            ->where('type', 'delete_request')
            ->where('status', 'approved')
            ->first();

        if (!$approvedNotification) {
            // Redirigir con mensaje
            return redirect()->back()
                ->with('error', 'No tiene permiso para eliminar este registro. La solicitud de eliminación no ha sido aprobada por el administrador.');
        }

        try {
            $maintenance->delete();
            
            // Eliminar la notificación de aprobación
            $approvedNotification->delete();
            
            // Redirigir con mensaje
            return redirect()->route('aprendiz.machinery.maintenance.index')
                ->with('success', 'Registro de mantenimiento eliminado exitosamente.');
        } catch (\Exception $e) {
            // Redirigir con mensaje
            return redirect()->back()
                ->with('error', 'Error al eliminar el registro: ' . $e->getMessage());
        }
    }

    // Generate PDF for all maintenances (o solo
    public function downloadAllMaintenancesPDF(Request $request)
    {
        $query = Maintenance::with('machinery')->orderBy('date', 'desc');

        if ($request->filled('ids')) {
            $ids = array_filter(array_map('intval', explode(',', $request->ids)));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        $maintenances = $query->get();

        $pdf = PDF::loadView('aprendiz.machinery.maintenances.pdf.all-maintenances', compact('maintenances'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('todos_los_mantenimientos_' . date('Y-m-d') . '.pdf');
    }

    // Generate PDF for individual maintenance
    public function downloadMaintenancePDF(Maintenance $maintenance)
    {
        $maintenance->load('machinery');
        
        // Convertir imagen a base64 si existe
        $imageBase64 = null;
        if ($maintenance->machinery && $maintenance->machinery->image && file_exists(upload_base_path('storage/' . $maintenance->machinery->image))) {
            $imagePath = upload_base_path('storage/' . $maintenance->machinery->image);
            $imageData = file_get_contents($imagePath);
            $imageInfo = getimagesize($imagePath);
            $mimeType = $imageInfo['mime'];
            $imageBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        }
        
        $pdf = PDF::loadView('aprendiz.machinery.maintenances.pdf.maintenance-details', compact('maintenance', 'imageBase64'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('mantenimiento_' . $maintenance->id . '_' . date('Y-m-d') . '.pdf');
    }

    // Devuelve la fecha/hora del próximo mantenimiento por
    public function nextMaintenanceDue(Request $request)
    {
        $machineryId = $request->query('machinery_id');
        if (!$machineryId) {
            return response()->json(['error' => 'machinery_id requerido'], 400);
        }
        $machinery = Machinery::find($machineryId);
        if (!$machinery) {
            return response()->json(['error' => 'Maquinaria no encontrada'], 404);
        }
        $nextDue = $machinery->getNextMaintenanceDueDateTime();
        if (!$nextDue) {
            return response()->json([
                'next_due' => null,
                'frequency' => $machinery->maint_freq,
                'seconds_remaining' => null,
                'due_now' => false,
                'paused' => true, // Maquinaria en mantenimiento: cronómetro pausado
            ]);
        }
        $dueNow = now()->gte($nextDue);
        $secondsRemaining = $dueNow ? 0 : (int) now()->diffInSeconds($nextDue, false);
        return response()->json([
            'next_due' => $nextDue->toIso8601String(),
            'frequency' => $machinery->maint_freq,
            'seconds_remaining' => $secondsRemaining,
            'due_now' => $dueNow,
            'paused' => false,
        ]);
    }
}

