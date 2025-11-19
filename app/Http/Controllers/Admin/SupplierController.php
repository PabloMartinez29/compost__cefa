<?php

namespace App\Http\Controllers\Admin;

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
        
        return view('admin.machinery.suppliers.index', compact('suppliers', 'totalSuppliers', 'todaySuppliers', 'thisMonthSuppliers', 'totalMachineries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Solo mostrar maquinarias que NO tienen proveedor registrado
        $machineries = Machinery::whereDoesntHave('supplier')->orderBy('name')->get();
        return view('admin.machinery.suppliers.create', compact('machineries'));
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
            
            return redirect()->route('admin.machinery.supplier.index')
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
        
        return view('admin.machinery.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        // Mostrar todas las maquinarias, incluyendo la que ya tiene este proveedor
        // para permitir cambiar de maquinaria si es necesario
        $machineries = Machinery::orderBy('name')->get();
        return view('admin.machinery.suppliers.edit', compact('supplier', 'machineries'));
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
            
            return redirect()->route('admin.machinery.supplier.index')
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
    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            
            return redirect()->route('admin.machinery.supplier.index')
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
        
        $pdf = PDF::loadView('admin.machinery.suppliers.pdf.all-suppliers', compact('suppliers'))
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
        
        $pdf = PDF::loadView('admin.machinery.suppliers.pdf.supplier-details', compact('supplier', 'imageBase64'))
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
