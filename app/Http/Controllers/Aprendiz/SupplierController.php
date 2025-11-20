<?php

namespace App\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Machinery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Cargar la relación con maquinaria para mostrar la imagen
        $suppliers = Supplier::with('machinery')->latest()->get();
        
        // Statistics
        $totalSuppliers = Supplier::count();
        $todaySuppliers = Supplier::whereDate('created_at', today())->count();
        $thisMonthSuppliers = Supplier::whereMonth('created_at', now()->month)
                                      ->whereYear('created_at', now()->year)
                                      ->count();
        $totalMachineries = \App\Models\Machinery::count();
        
        // IDs de proveedores con aprobación vigente para eliminar
        $userId = auth()->check() ? auth()->id() : null;
        $approvedSupplierIds = \App\Models\Notification::where('user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'approved')
            ->whereNotNull('supplier_id')
            ->pluck('supplier_id')
            ->toArray();

        // IDs de proveedores con solicitud pendiente
        $pendingSupplierIds = \App\Models\Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'pending')
            ->whereNotNull('supplier_id')
            ->pluck('supplier_id')
            ->toArray();

        // IDs de proveedores con solicitud rechazada
        // Buscar notificaciones donde el aprendiz es user_id (respuesta del admin - nueva notificación creada)
        $rejectedAsUser = \App\Models\Notification::where('user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->whereNotNull('supplier_id')
            ->pluck('supplier_id')
            ->toArray();
        
        // Buscar notificaciones donde el aprendiz es from_user_id (notificación original actualizada)
        $rejectedAsFromUser = \App\Models\Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->whereNotNull('supplier_id')
            ->pluck('supplier_id')
            ->toArray();
        
        // Combinar ambos arrays y eliminar duplicados
        $rejectedSupplierIds = array_unique(array_merge($rejectedAsUser, $rejectedAsFromUser));

        return view('aprendiz.machinery.suppliers.index', compact(
            'suppliers', 
            'totalSuppliers', 
            'todaySuppliers', 
            'thisMonthSuppliers', 
            'totalMachineries',
            'approvedSupplierIds',
            'pendingSupplierIds',
            'rejectedSupplierIds'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Solo mostrar maquinarias que NO tienen proveedor registrado
        $machineries = Machinery::whereDoesntHave('supplier')->orderBy('name')->get();
        return view('aprendiz.machinery.suppliers.create', compact('machineries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'machinery_id' => 'required|exists:machineries,id',
            'maker' => 'required|string|max:150',
            'origin' => 'required|string|max:150',
            'purchase_date' => 'required|date|before_or_equal:today',
            'supplier' => 'required|string|max:150',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:150',
        ], [
            'machinery_id.required' => 'Debe seleccionar una maquinaria.',
            'machinery_id.exists' => 'La maquinaria seleccionada no existe.',
            'maker.required' => 'El fabricante es obligatorio.',
            'maker.max' => 'El fabricante no debe exceder 150 caracteres.',
            'origin.required' => 'El origen es obligatorio.',
            'origin.max' => 'El origen no debe exceder 150 caracteres.',
            'purchase_date.required' => 'La fecha de compra es obligatoria.',
            'purchase_date.date' => 'La fecha de compra debe ser una fecha válida.',
            'purchase_date.before_or_equal' => 'La fecha de compra no puede ser futura.',
            'supplier.required' => 'El nombre del proveedor es obligatorio.',
            'supplier.max' => 'El nombre del proveedor no debe exceder 150 caracteres.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.max' => 'El teléfono no debe exceder 50 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.max' => 'El correo electrónico no debe exceder 150 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Supplier::create($request->all());
            
            return redirect()->route('aprendiz.machinery.supplier.index')
                ->with('success', 'Proveedor registrado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al registrar el proveedor: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('machinery');
        
        // Si es una petición AJAX, devolver JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'id' => $supplier->id,
                'machinery_name' => $supplier->machinery->name ?? 'N/A',
                'maker' => $supplier->maker,
                'supplier' => $supplier->supplier,
                'origin' => $supplier->origin,
                'purchase_date' => $supplier->purchase_date->format('Y-m-d'),
                'purchase_date_formatted' => $supplier->purchase_date->format('d/m/Y'),
                'phone' => $supplier->phone,
                'email' => $supplier->email,
                'created_at' => $supplier->created_at->format('d/m/Y H:i:s'),
                'created_at_formatted' => $supplier->created_at->format('d/m/Y H:i:s'),
                'machinery_image_url' => $supplier->machinery && $supplier->machinery->image 
                    ? \Illuminate\Support\Facades\Storage::url($supplier->machinery->image) 
                    : null,
            ]);
        }
        
        return view('aprendiz.machinery.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        // Mostrar todas las maquinarias, incluyendo la que ya tiene este proveedor
        // para permitir cambiar de maquinaria si es necesario
        $machineries = Machinery::orderBy('name')->get();
        return view('aprendiz.machinery.suppliers.edit', compact('supplier', 'machineries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validator = Validator::make($request->all(), [
            'machinery_id' => 'required|exists:machineries,id',
            'maker' => 'required|string|max:150',
            'origin' => 'required|string|max:150',
            'purchase_date' => 'required|date|before_or_equal:today',
            'supplier' => 'required|string|max:150',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:150',
        ], [
            'machinery_id.required' => 'Debe seleccionar una maquinaria.',
            'machinery_id.exists' => 'La maquinaria seleccionada no existe.',
            'maker.required' => 'El fabricante es obligatorio.',
            'maker.max' => 'El fabricante no debe exceder 150 caracteres.',
            'origin.required' => 'El origen es obligatorio.',
            'origin.max' => 'El origen no debe exceder 150 caracteres.',
            'purchase_date.required' => 'La fecha de compra es obligatoria.',
            'purchase_date.date' => 'La fecha de compra debe ser una fecha válida.',
            'purchase_date.before_or_equal' => 'La fecha de compra no puede ser futura.',
            'supplier.required' => 'El nombre del proveedor es obligatorio.',
            'supplier.max' => 'El nombre del proveedor no debe exceder 150 caracteres.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.max' => 'El teléfono no debe exceder 50 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.max' => 'El correo electrónico no debe exceder 150 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $supplier->update($request->all());
            
            return redirect()->route('aprendiz.machinery.supplier.index')
                ->with('success', 'Proveedor actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el proveedor: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Request permission to delete supplier
     */
    public function requestDeletePermission(Supplier $supplier)
    {
        $currentUserId = auth()->check() ? auth()->id() : null;

        $existing = \App\Models\Notification::where('from_user_id', $currentUserId)
            ->where('supplier_id', $supplier->id)
            ->where('type', 'delete_request')
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
        if ($existing && $existing->status === 'pending') {
            return redirect()->route('aprendiz.machinery.supplier.index')
                ->with('permission_required', 'Su solicitud de eliminación ya está pendiente de aprobación del administrador.');
        }
        
        if ($existing && $existing->status === 'approved') {
            return redirect()->route('aprendiz.machinery.supplier.index')
                ->with('success', 'Su solicitud ya fue aprobada. Ahora puede eliminar el registro.');
        }

        $rejected = \App\Models\Notification::where('from_user_id', $currentUserId)
            ->where('supplier_id', $supplier->id)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->first();
        
        if ($rejected) {
            $rejected->delete();
        }

        $admins = \App\Models\User::where('role', 'admin')->get();
        
        if ($admins->isNotEmpty()) {
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'from_user_id' => $currentUserId,
                    'supplier_id' => $supplier->id,
                    'type' => 'delete_request',
                    'status' => 'pending',
                    'message' => (auth()->check() ? auth()->user()->name : 'Usuario') . ' solicita permiso para eliminar el proveedor #' . str_pad($supplier->id, 3, '0', STR_PAD_LEFT)
                ]);
            }
        }

        return redirect()->route('aprendiz.machinery.supplier.index')
            ->with('success', 'Solicitud de eliminación enviada al administrador. Recibirá una notificación cuando sea aprobada.');
    }

    /**
     * Check delete permission status
     */
    public function checkDeletePermissionStatus(Supplier $supplier)
    {
        $currentUserId = auth()->check() ? auth()->id() : null;
        
        $responseNotification = \App\Models\Notification::where('user_id', $currentUserId)
            ->where('supplier_id', $supplier->id)
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
            ->where('supplier_id', $supplier->id)
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

    public function destroy(Supplier $supplier)
    {
        $currentUserId = auth()->check() ? auth()->id() : null;

        $approvedNotification = \App\Models\Notification::where('user_id', $currentUserId)
            ->where('supplier_id', $supplier->id)
            ->where('type', 'delete_request')
            ->where('status', 'approved')
            ->first();

        if (!$approvedNotification) {
            return redirect()->back()
                ->with('error', 'No tiene permiso para eliminar este registro. La solicitud de eliminación no ha sido aprobada por el administrador.');
        }

        try {
            $supplier->delete();
            
            $approvedNotification->delete();
            
            return redirect()->route('aprendiz.machinery.supplier.index')
                ->with('success', 'Proveedor eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el proveedor: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF for all suppliers
     */
    public function downloadAllSuppliersPDF()
    {
        $suppliers = Supplier::with('machinery')->latest()->get();
        
        $pdf = PDF::loadView('aprendiz.machinery.suppliers.pdf.all-suppliers', compact('suppliers'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('todos_los_proveedores_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate PDF for individual supplier
     */
    public function downloadSupplierPDF(Supplier $supplier)
    {
        $supplier->load('machinery');
        
        // Convertir imagen a base64 si existe
        $imageBase64 = null;
        if ($supplier->machinery && $supplier->machinery->image && Storage::disk('public')->exists($supplier->machinery->image)) {
            $imagePath = Storage::disk('public')->path($supplier->machinery->image);
            $imageData = file_get_contents($imagePath);
            $imageInfo = getimagesize($imagePath);
            $mimeType = $imageInfo['mime'];
            $imageBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        }
        
        $pdf = PDF::loadView('aprendiz.machinery.suppliers.pdf.supplier-details', compact('supplier', 'imageBase64'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('proveedor_' . str_replace(' ', '_', $supplier->supplier) . '_' . date('Y-m-d') . '.pdf');
    }
}

