<?php

namespace App\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use App\Models\Machinery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class MachineryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $machineries = Machinery::latest()->get();
        
        // IDs de maquinarias con aprobación vigente para eliminar
        $userId = auth()->check() ? auth()->id() : null;
        $approvedMachineryIds = \App\Models\Notification::where('user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'approved')
            ->whereNotNull('machinery_id')
            ->pluck('machinery_id')
            ->toArray();

        // IDs de maquinarias con solicitud pendiente
        $pendingMachineryIds = \App\Models\Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'pending')
            ->whereNotNull('machinery_id')
            ->pluck('machinery_id')
            ->toArray();

        // IDs de maquinarias con solicitud rechazada
        $rejectedMachineryIds = \App\Models\Notification::where('user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->whereNotNull('machinery_id')
            ->pluck('machinery_id')
            ->toArray();
        
        // También verificar notificaciones pendientes que fueron rechazadas
        $rejectedFromPending = \App\Models\Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->whereNotNull('machinery_id')
            ->pluck('machinery_id')
            ->toArray();
        
        $rejectedMachineryIds = array_unique(array_merge($rejectedMachineryIds, $rejectedFromPending));

        return view('aprendiz.machinery.machineries.index', compact(
            'machineries',
            'approvedMachineryIds',
            'pendingMachineryIds',
            'rejectedMachineryIds'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('aprendiz.machinery.machineries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'location' => 'required|string|max:150',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'serial' => 'required|string|max:100|unique:machineries,serial',
            'start_func' => 'required|date|before_or_equal:today',
            'maint_freq' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ], [
            'name.required' => 'El nombre de la maquinaria es obligatorio.',
            'name.max' => 'El nombre no debe exceder 150 caracteres.',
            'location.required' => 'La ubicación es obligatoria.',
            'location.max' => 'La ubicación no debe exceder 150 caracteres.',
            'brand.required' => 'La marca es obligatoria.',
            'brand.max' => 'La marca no debe exceder 100 caracteres.',
            'model.required' => 'El modelo es obligatorio.',
            'model.max' => 'El modelo no debe exceder 100 caracteres.',
            'serial.required' => 'El número de serie es obligatorio.',
            'serial.max' => 'El número de serie no debe exceder 100 caracteres.',
            'serial.unique' => 'Este número de serie ya está registrado.',
            'start_func.required' => 'La fecha de inicio de funcionamiento es obligatoria.',
            'start_func.date' => 'La fecha de inicio debe ser una fecha válida.',
            'start_func.before_or_equal' => 'La fecha de inicio no puede ser futura.',
            'maint_freq.required' => 'La frecuencia de mantenimiento es obligatoria.',
            'maint_freq.max' => 'La frecuencia de mantenimiento no debe exceder 100 caracteres.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif o webp.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('machineries', 'public');
            }
            
            Machinery::create($data);
            
            return redirect()->route('aprendiz.machinery.index')
                ->with('success', 'Maquinaria registrada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al registrar la maquinaria: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Machinery $machinery)
    {
        return view('aprendiz.machinery.machineries.show', compact('machinery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Machinery $machinery)
    {
        return view('aprendiz.machinery.machineries.edit', compact('machinery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Machinery $machinery)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'location' => 'required|string|max:150',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'serial' => 'required|string|max:100|unique:machineries,serial,' . $machinery->id,
            'start_func' => 'required|date|before_or_equal:today',
            'maint_freq' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ], [
            'name.required' => 'El nombre de la maquinaria es obligatorio.',
            'name.max' => 'El nombre no debe exceder 150 caracteres.',
            'location.required' => 'La ubicación es obligatoria.',
            'location.max' => 'La ubicación no debe exceder 150 caracteres.',
            'brand.required' => 'La marca es obligatoria.',
            'brand.max' => 'La marca no debe exceder 100 caracteres.',
            'model.required' => 'El modelo es obligatorio.',
            'model.max' => 'El modelo no debe exceder 100 caracteres.',
            'serial.required' => 'El número de serie es obligatorio.',
            'serial.max' => 'El número de serie no debe exceder 100 caracteres.',
            'serial.unique' => 'Este número de serie ya está registrado.',
            'start_func.required' => 'La fecha de inicio de funcionamiento es obligatoria.',
            'start_func.date' => 'La fecha de inicio debe ser una fecha válida.',
            'start_func.before_or_equal' => 'La fecha de inicio no puede ser futura.',
            'maint_freq.required' => 'La frecuencia de mantenimiento es obligatoria.',
            'maint_freq.max' => 'La frecuencia de mantenimiento no debe exceder 100 caracteres.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif o webp.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($machinery->image) {
                    Storage::disk('public')->delete($machinery->image);
                }
                $data['image'] = $request->file('image')->store('machineries', 'public');
            }
            
            $machinery->update($data);
            
            return redirect()->route('aprendiz.machinery.index')
                ->with('success', 'Maquinaria actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la maquinaria: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Request permission to delete machinery
     */
    public function requestDeletePermission(Machinery $machinery)
    {
        $currentUserId = auth()->check() ? auth()->id() : null;

        // Evitar solicitudes duplicadas si ya hay una pendiente o aprobada
        $existing = \App\Models\Notification::where('from_user_id', $currentUserId)
            ->where('machinery_id', $machinery->id)
            ->where('type', 'delete_request')
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
        if ($existing && $existing->status === 'pending') {
            return redirect()->route('aprendiz.machinery.index')
                ->with('permission_required', 'Su solicitud de eliminación ya está pendiente de aprobación del administrador.');
        }
        
        if ($existing && $existing->status === 'approved') {
            return redirect()->route('aprendiz.machinery.index')
                ->with('success', 'Su solicitud ya fue aprobada. Ahora puede eliminar el registro.');
        }

        // Si hay una solicitud rechazada, eliminarla para permitir nueva solicitud
        $rejected = \App\Models\Notification::where('from_user_id', $currentUserId)
            ->where('machinery_id', $machinery->id)
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
                    'machinery_id' => $machinery->id,
                    'type' => 'delete_request',
                    'status' => 'pending',
                    'message' => (auth()->check() ? auth()->user()->name : 'Usuario') . ' solicita permiso para eliminar la maquinaria: ' . $machinery->name
                ]);
            }
        } else {
            \Log::warning('No admin users found to send notification to');
        }

        return redirect()->route('aprendiz.machinery.index')
            ->with('success', 'Solicitud de eliminación enviada al administrador. Recibirá una notificación cuando sea aprobada.');
    }

    /**
     * Check delete permission status
     */
    public function checkDeletePermissionStatus(Machinery $machinery)
    {
        $currentUserId = auth()->check() ? auth()->id() : null;
        
        // Primero, buscar una notificación de respuesta del admin (donde el aprendiz es user_id)
        $responseNotification = \App\Models\Notification::where('user_id', $currentUserId)
            ->where('machinery_id', $machinery->id)
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

        // Si no hay una notificación de respuesta, buscar la solicitud pendiente original
        $pendingRequest = \App\Models\Notification::where('from_user_id', $currentUserId)
            ->where('machinery_id', $machinery->id)
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
            'message' => 'No hay solicitudes pendientes para esta maquinaria.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Machinery $machinery)
    {
        $currentUserId = auth()->check() ? auth()->id() : null;

        // Verificar si hay una notificación de aprobación para este registro
        $approvedNotification = \App\Models\Notification::where('user_id', $currentUserId)
            ->where('machinery_id', $machinery->id)
            ->where('type', 'delete_request')
            ->where('status', 'approved')
            ->first();

        if (!$approvedNotification) {
            return redirect()->back()
                ->with('error', 'No tiene permiso para eliminar esta maquinaria. La solicitud de eliminación no ha sido aprobada por el administrador.');
        }

        try {
            // Delete image if exists
            if ($machinery->image && Storage::disk('public')->exists($machinery->image)) {
                Storage::disk('public')->delete($machinery->image);
            }
            
            $machinery->delete();
            
            // Eliminar la notificación de aprobación después de la eliminación exitosa
            $approvedNotification->delete();
            
            return redirect()->route('aprendiz.machinery.index')
                ->with('success', 'Maquinaria eliminada exitosamente.');
        } catch (\Exception $e) {
            \Log::error('Error deleting machinery: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al eliminar la maquinaria. Por favor, intente nuevamente.');
        }
    }

    /**
     * Generate PDF for all machineries
     */
    public function downloadAllMachineriesPDF()
    {
        $machineries = Machinery::latest()->get();
        
        $pdf = PDF::loadView('aprendiz.machinery.machineries.pdf.all-machineries', compact('machineries'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('todas_las_maquinarias_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate PDF for individual machinery
     */
    public function downloadMachineryPDF(Machinery $machinery)
    {
        // Convertir imagen a base64 si existe
        $imageBase64 = null;
        if ($machinery->image && Storage::disk('public')->exists($machinery->image)) {
            $imagePath = Storage::disk('public')->path($machinery->image);
            $imageData = file_get_contents($imagePath);
            $imageInfo = getimagesize($imagePath);
            $mimeType = $imageInfo['mime'];
            $imageBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        }
        
        $pdf = PDF::loadView('aprendiz.machinery.machineries.pdf.machinery-details', compact('machinery', 'imageBase64'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('maquinaria_' . str_replace(' ', '_', $machinery->name) . '_' . date('Y-m-d') . '.pdf');
    }
}

