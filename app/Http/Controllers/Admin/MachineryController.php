<?php

// Controlador Admin MachineryController — CRUD de maquinaria
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Machinery;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class MachineryController extends Controller
{
    // Listar todos los registros
    public function index()
    {
        $machineries = Machinery::latest()->get();
        // Mostrar vista
        return view('admin.machinery.machineries.index', compact('machineries'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        // Mostrar vista
        return view('admin.machinery.machineries.create');
    }

    // Guardar nuevo registro
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
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
            'image.required' => 'La imagen es obligatoria.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif o webp.',
        ]);

        if ($validator->fails()) {
            // Redirigir con mensaje
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            
            // Procesar archivo
        if ($request->hasFile('image')) {
                $archivo = $request->file('image');
                $nombre = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $archivo->getClientOriginalName());
                $dir = upload_base_path('storage/machineries');
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                $archivo->move($dir, $nombre);
                $data['image'] = 'machineries/' . $nombre;
            }
            
            $machinery = Machinery::create($data);
            $machinery->scheduleNextMaintenanceDue();
            
            // Redirigir con mensaje
            return redirect()->route('admin.machinery.index')
                ->with('success', 'Maquinaria registrada exitosamente.');
        } catch (\Exception $e) {
            // Redirigir con mensaje
            return redirect()->back()
                ->with('error', 'Error al registrar la maquinaria: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Mostrar detalle del registro
    public function show(Machinery $machinery)
    {
        // Mostrar vista
        return view('admin.machinery.machineries.show', compact('machinery'));
    }

    // Mostrar formulario de edición
    public function edit(Machinery $machinery)
    {
        // Mostrar vista
        return view('admin.machinery.machineries.edit', compact('machinery'));
    }

    // Actualizar registro existente
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
            // Redirigir con mensaje
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->except(['image']);
            
            // Procesar archivo
        if ($request->hasFile('image')) {
                if ($machinery->image && file_exists(upload_base_path('storage/' . $machinery->image))) {
                    unlink(upload_base_path('storage/' . $machinery->image));
                }
                $archivo = $request->file('image');
                $nombre = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $archivo->getClientOriginalName());
                $dir = upload_base_path('storage/machineries');
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                $archivo->move($dir, $nombre);
                $data['image'] = 'machineries/' . $nombre;
            } else {
                $data['image'] = $machinery->image;
            }
            
            $machinery->update($data);
            // Al editar (p. ej. cambiar frecuencia Semanal → Diario) se reinicia el cronómetro con la nueva frecuencia
            $machinery->scheduleNextMaintenanceDue();
            
            // Redirigir con mensaje
            return redirect()->route('admin.machinery.index')
                ->with('success', 'Maquinaria actualizada exitosamente.');
        } catch (\Exception $e) {
            // Redirigir con mensaje
            return redirect()->back()
                ->with('error', 'Error al actualizar la maquinaria: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Eliminar registro del sistema
    public function destroy(Machinery $machinery)
    {
        try {
            if ($machinery->image && file_exists(upload_base_path('storage/' . $machinery->image))) {
                unlink(upload_base_path('storage/' . $machinery->image));
            }
            
            $machinery->delete();
            
            // Redirigir con mensaje
            return redirect()->route('admin.machinery.index')
                ->with('success', 'Maquinaria eliminada exitosamente.');
        } catch (\Exception $e) {
            \Log::error('Error al eliminar maquinaria: ' . $e->getMessage());
            // Redirigir con mensaje
            return redirect()->route('admin.machinery.index')
                ->with('error', 'Error al eliminar la maquinaria. Por favor, intente nuevamente.');
        }
    }

    // Get machinery statistics for dashboard
    public function getStats()
    {
        $total = Machinery::count();
        $operational = Machinery::whereHas('maintenances', function($query) {
            $query->where('created_at', '>=', now()->subDays(30));
        })->count();
        $needsMaintenance = $total - $operational;

        return [
            'total' => $total,
            'operational' => $operational,
            'needs_maintenance' => $needsMaintenance
        ];
    }

    // Generate PDF for all machineries (o solo
    public function downloadAllMachineriesPDF(Request $request)
    {
        $query = Machinery::latest();

        if ($request->filled('ids')) {
            $ids = array_filter(array_map('intval', explode(',', $request->ids)));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        $machineries = $query->get();

        $pdf = PDF::loadView('admin.machinery.machineries.pdf.all-machineries', compact('machineries'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('todas_las_maquinarias_' . date('Y-m-d') . '.pdf');
    }

    // Generate PDF for individual machinery
    public function downloadMachineryPDF(Machinery $machinery)
    {
        // Convertir imagen a base64 si existe
        $imageBase64 = null;
        if ($machinery->image && file_exists(upload_base_path('storage/' . $machinery->image))) {
            $imagePath = upload_base_path('storage/' . $machinery->image);
            $imageData = file_get_contents($imagePath);
            $imageInfo = getimagesize($imagePath);
            $mimeType = $imageInfo['mime'];
            $imageBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        }
        
        $pdf = PDF::loadView('admin.machinery.machineries.pdf.machinery-details', compact('machinery', 'imageBase64'))
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
