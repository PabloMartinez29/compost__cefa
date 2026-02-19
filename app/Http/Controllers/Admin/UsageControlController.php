<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UsageControl;
use App\Models\Machinery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class UsageControlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usageControls = UsageControl::with('machinery')
            ->orderBy('date', 'desc')
            ->get();
        
        // Statistics
        $totalUsageControls = UsageControl::count();
        $todayUsageControls = UsageControl::whereDate('created_at', today())->count();
        $thisMonthUsageControls = UsageControl::whereMonth('date', now()->month)
                                              ->whereYear('date', now()->year)
                                              ->count();
        $totalHours = UsageControl::sum('hours');
        
        return view('admin.machinery.usage-controls.index', compact(
            'usageControls', 
            'totalUsageControls', 
            'todayUsageControls', 
            'thisMonthUsageControls',
            'totalHours'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mostrar solo maquinarias que:
        // 1. NO tienen registros activos de uso (sin fecha de fin)
        // 2. NO están en mantenimiento según Control de Actividades (maintenances con tipo 'M' sin fecha de fin)
        // 3. NO están en mantenimiento según Control de Uso Diario (usage_controls con status 'mantenimiento')
        
        // Excluir maquinarias con mantenimientos activos (tipo 'M' sin fecha de fin) en Control de Actividades
        $machineries = Machinery::whereDoesntHave('maintenances', function($query) {
            $query->where('type', 'M')
                  ->whereNull('end_date');
        })
        // Excluir maquinarias con registros activos de uso (sin fecha de fin) O con status 'mantenimiento'
        ->whereDoesntHave('usageControls', function($query) {
            $query->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('status', 'mantenimiento');
            });
        })
        ->orderBy('name')
        ->get();
        
        return view('admin.machinery.usage-controls.create', compact('machineries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'machinery_id' => 'required|exists:machineries,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'hours' => 'nullable|numeric|min:0',
            'responsible' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
        ], [
            'machinery_id.required' => 'Debe seleccionar una maquinaria.',
            'machinery_id.exists' => 'La maquinaria seleccionada no existe.',
            'start_date.required' => 'La fecha/hora de inicio es obligatoria.',
            'start_date.date' => 'La fecha/hora de inicio debe ser válida.',
            'end_date.date' => 'La fecha/hora de fin debe ser válida.',
            'end_date.after_or_equal' => 'La fecha/hora de fin debe ser posterior o igual a la de inicio.',
            'hours.numeric' => 'El total de horas debe ser un número.',
            'hours.min' => 'El total de horas no puede ser negativo.',
            'responsible.required' => 'El responsable es obligatorio.',
            'responsible.max' => 'El nombre del responsable no debe exceder 150 caracteres.',
            'description.max' => 'Las observaciones no deben exceder 1000 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->only(['machinery_id', 'start_date', 'end_date', 'hours', 'responsible', 'description']);
            // Convertir datetime-local a formato datetime para la base de datos
            $startDate = \Carbon\Carbon::parse($request->start_date);
            
            $data['start_date'] = $startDate->format('Y-m-d H:i:s');
            // Usar la fecha de inicio como 'date' para compatibilidad
            $data['date'] = $startDate->format('Y-m-d');
            
            // Si hay fecha de fin, convertirla y calcular horas
            if ($request->end_date) {
                $endDate = \Carbon\Carbon::parse($request->end_date);
                $data['end_date'] = $endDate->format('Y-m-d H:i:s');
                
                // Calcular horas automáticamente si hay fecha de fin
                $diffHours = $startDate->diffInHours($endDate);
                $data['hours'] = round($diffHours, 2);
            } else {
                // Si no hay fecha de fin, dejar end_date y hours como null
                $data['end_date'] = null;
                $data['hours'] = $request->hours ?? 0;
            }
            
            UsageControl::create($data);
            
            return redirect()->route('admin.machinery.usage-control.index')
                ->with('success', 'Registro de uso del equipo creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el registro: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UsageControl $usageControl)
    {
        $usageControl->load('machinery');
        
        // Si es una petición AJAX, devolver JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'id' => $usageControl->id,
                'machinery_name' => $usageControl->machinery->name ?? 'N/A',
                'machinery_brand' => $usageControl->machinery->brand ?? 'N/A',
                'machinery_model' => $usageControl->machinery->model ?? 'N/A',
                'date' => $usageControl->start_date ? $usageControl->start_date->format('Y-m-d') : ($usageControl->date ? $usageControl->date->format('Y-m-d') : null),
                'date_formatted' => $usageControl->start_date ? $usageControl->start_date->format('d/m/Y') : ($usageControl->date ? $usageControl->date->format('d/m/Y') : 'N/A'),
                'start_date' => $usageControl->start_date ? $usageControl->start_date->format('Y-m-d H:i:s') : null,
                'start_date_formatted' => $usageControl->start_date ? $usageControl->start_date->setTimezone('America/Bogota')->format('d/m/Y h:i A') : 'N/A',
                'end_date' => $usageControl->end_date ? $usageControl->end_date->format('Y-m-d H:i:s') : null,
                'end_date_formatted' => $usageControl->end_date ? $usageControl->end_date->setTimezone('America/Bogota')->format('d/m/Y h:i A') : 'N/A',
                'hours' => $usageControl->hours,
                'responsible' => $usageControl->responsible,
                'description' => $usageControl->description,
                'created_at' => $usageControl->created_at->format('d/m/Y H:i:s'),
                'created_at_formatted' => $usageControl->created_at->format('d/m/Y H:i:s'),
                'machinery_image_url' => $usageControl->machinery && $usageControl->machinery->image 
                    ? asset($usageControl->machinery->image) 
                    : null,
            ]);
        }
        
        return view('admin.machinery.usage-controls.show', compact('usageControl'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UsageControl $usageControl)
    {
        // En edición, mostrar maquinarias que NO están en mantenimiento
        // (excepto la maquinaria actual del registro que se está editando)
        $currentMachineryId = $usageControl->machinery_id;
        
        // Obtener todas las maquinarias que NO están en mantenimiento
        $query = Machinery::whereDoesntHave('maintenances', function($q) {
            $q->where('type', 'M')->whereNull('end_date');
        });
        if (Schema::hasColumn('usage_controls', 'status')) {
            $query->whereDoesntHave('usageControls', function($q) {
                $q->where('status', 'mantenimiento');
            });
        }
        $availableMachineries = $query->orderBy('name')->get();
        
        // Siempre incluir la maquinaria actual del registro (aunque esté en mantenimiento)
        $currentMachinery = Machinery::find($currentMachineryId);
        $machineries = $availableMachineries;
        
        if ($currentMachinery && !$machineries->contains('id', $currentMachineryId)) {
            $machineries = $machineries->push($currentMachinery)->sortBy('name')->values();
        }
        
        // Si es una petición AJAX, devolver JSON con maquinarias
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'usageControl' => [
                    'id' => $usageControl->id,
                    'machinery_id' => $usageControl->machinery_id,
                    'responsible' => $usageControl->responsible,
                    'start_date' => $usageControl->start_date ? $usageControl->start_date->format('Y-m-d\TH:i') : null,
                    'end_date' => $usageControl->end_date ? $usageControl->end_date->format('Y-m-d\TH:i') : null,
                    'hours' => $usageControl->hours,
                    'description' => $usageControl->description,
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
        
        return view('admin.machinery.usage-controls.edit', compact('usageControl', 'machineries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UsageControl $usageControl)
    {
        $validator = Validator::make($request->all(), [
            'machinery_id' => 'required|exists:machineries,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'hours' => 'nullable|numeric|min:0',
            'responsible' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
        ], [
            'machinery_id.required' => 'Debe seleccionar una maquinaria.',
            'machinery_id.exists' => 'La maquinaria seleccionada no existe.',
            'start_date.required' => 'La fecha/hora de inicio es obligatoria.',
            'start_date.date' => 'La fecha/hora de inicio debe ser válida.',
            'end_date.date' => 'La fecha/hora de fin debe ser válida.',
            'end_date.after_or_equal' => 'La fecha/hora de fin debe ser posterior o igual a la de inicio.',
            'hours.numeric' => 'El total de horas debe ser un número.',
            'hours.min' => 'El total de horas no puede ser negativo.',
            'responsible.required' => 'El responsable es obligatorio.',
            'responsible.max' => 'El nombre del responsable no debe exceder 150 caracteres.',
            'description.max' => 'Las observaciones no deben exceder 1000 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->only(['machinery_id', 'start_date', 'end_date', 'hours', 'responsible', 'description']);
            // Convertir datetime-local a formato datetime para la base de datos
            $startDate = \Carbon\Carbon::parse($request->start_date);
            
            $data['start_date'] = $startDate->format('Y-m-d H:i:s');
            // Usar la fecha de inicio como 'date' para compatibilidad
            $data['date'] = $startDate->format('Y-m-d');
            
            // Si hay fecha de fin, convertirla y calcular horas
            if ($request->end_date) {
                $endDate = \Carbon\Carbon::parse($request->end_date);
                $data['end_date'] = $endDate->format('Y-m-d H:i:s');
                
                // Calcular horas automáticamente si hay fecha de fin
                $diffHours = $startDate->diffInHours($endDate);
                $data['hours'] = round($diffHours, 2);
            } else {
                // Si no hay fecha de fin, dejar end_date como null
                $data['end_date'] = null;
                // Mantener las horas existentes si no hay fecha de fin
                $data['hours'] = $request->hours ?? $usageControl->hours ?? 0;
            }
            
            $usageControl->update($data);
            
            return redirect()->route('admin.machinery.usage-control.index')
                ->with('success', 'Registro de uso del equipo actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el registro: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UsageControl $usageControl)
    {
        try {
            $usageControl->delete();
            
            return redirect()->route('admin.machinery.usage-control.index')
                ->with('success', 'Registro de uso del equipo eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el registro: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF for all usage controls (o solo los filtrados si se pasan ids)
     */
    public function downloadAllUsageControlsPDF(Request $request)
    {
        $query = UsageControl::with('machinery')->orderBy('date', 'desc');

        if ($request->filled('ids')) {
            $ids = array_filter(array_map('intval', explode(',', $request->ids)));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        $usageControls = $query->get();

        $pdf = PDF::loadView('admin.machinery.usage-controls.pdf.all-usage-controls', compact('usageControls'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('todos_los_controles_de_uso_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate PDF for individual usage control
     */
    public function downloadUsageControlPDF(UsageControl $usageControl)
    {
        $usageControl->load('machinery');
        
        // Convertir imagen a base64 si existe
        $imageBase64 = null;
        if ($usageControl->machinery && $usageControl->machinery->image && file_exists(public_path($usageControl->machinery->image))) {
            $imagePath = public_path($usageControl->machinery->image);
            $imageData = file_get_contents($imagePath);
            $imageInfo = getimagesize($imagePath);
            $mimeType = $imageInfo['mime'];
            $imageBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        }
        
        $pdf = PDF::loadView('admin.machinery.usage-controls.pdf.usage-control-details', compact('usageControl', 'imageBase64'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('control_uso_' . $usageControl->id . '_' . date('Y-m-d') . '.pdf');
    }
}


