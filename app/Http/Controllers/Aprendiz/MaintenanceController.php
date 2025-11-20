<?php

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
    /**
     * Display a listing of the resource.
     */
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

        return view('aprendiz.machinery.maintenances.index', compact(
            'maintenances', 
            'totalMaintenances', 
            'todayMaintenances', 
            'thisMonthMaintenances',
            'maintenanceCount',
            'operationsCount',
            'approvedMaintenanceIds',
            'pendingMaintenanceIds',
            'rejectedMaintenanceIds'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mostrar todas las maquinarias, incluso las que ya tienen registros
        $machineries = Machinery::orderBy('name')->get();
        return view('aprendiz.machinery.maintenances.create', compact('machineries'));
    }

    /**
     * Store a newly created resource in storage.
     */
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
            
            Maintenance::create($data);
            
            return redirect()->route('aprendiz.machinery.maintenance.index')
                ->with('success', 'Registro de mantenimiento creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el registro: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
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
        
        return view('aprendiz.machinery.maintenances.show', compact('maintenance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maintenance $maintenance)
    {
        $machineries = Machinery::orderBy('name')->get();
        return view('aprendiz.machinery.maintenances.edit', compact('maintenance', 'machineries'));
    }

    /**
     * Update the specified resource in storage.
     */
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
            
            $maintenance->update($data);
            
            return redirect()->route('aprendiz.machinery.maintenance.index')
                ->with('success', 'Registro de mantenimiento actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el registro: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Request permission to delete maintenance
     */
    public function requestDeletePermission(Maintenance $maintenance)
    {
        $currentUserId = auth()->check() ? auth()->id() : null;

        // Evitar solicitudes duplicadas
        $existing = \App\Models\Notification::where('from_user_id', $currentUserId)
            ->where('maintenance_id', $maintenance->id)
            ->where('type', 'delete_request')
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
        if ($existing && $existing->status === 'pending') {
            return redirect()->route('aprendiz.machinery.maintenance.index')
                ->with('permission_required', 'Su solicitud de eliminación ya está pendiente de aprobación del administrador.');
        }
        
        if ($existing && $existing->status === 'approved') {
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

        return redirect()->route('aprendiz.machinery.maintenance.index')
            ->with('success', 'Solicitud de eliminación enviada al administrador. Recibirá una notificación cuando sea aprobada.');
    }

    /**
     * Check delete permission status
     */
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

        // Verificar si hay una notificación de aprobación
        $approvedNotification = \App\Models\Notification::where('user_id', $currentUserId)
            ->where('maintenance_id', $maintenance->id)
            ->where('type', 'delete_request')
            ->where('status', 'approved')
            ->first();

        if (!$approvedNotification) {
            return redirect()->back()
                ->with('error', 'No tiene permiso para eliminar este registro. La solicitud de eliminación no ha sido aprobada por el administrador.');
        }

        try {
            $maintenance->delete();
            
            // Eliminar la notificación de aprobación
            $approvedNotification->delete();
            
            return redirect()->route('aprendiz.machinery.maintenance.index')
                ->with('success', 'Registro de mantenimiento eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el registro: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF for all maintenances
     */
    public function downloadAllMaintenancesPDF()
    {
        $maintenances = Maintenance::with('machinery')->orderBy('date', 'desc')->get();
        
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

    /**
     * Generate PDF for individual maintenance
     */
    public function downloadMaintenancePDF(Maintenance $maintenance)
    {
        $maintenance->load('machinery');
        
        // Convertir imagen a base64 si existe
        $imageBase64 = null;
        if ($maintenance->machinery && $maintenance->machinery->image && Storage::disk('public')->exists($maintenance->machinery->image)) {
            $imagePath = Storage::disk('public')->path($maintenance->machinery->image);
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
}

