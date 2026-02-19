<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Composting;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class TrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verificar autenticación
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Obtener todas las pilas de compostaje (tanto del usuario como del administrador)
        $compostings = Composting::with(['trackings' => function($query) {
                $query->orderBy('day', 'asc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calcular estadísticas
        $totalPiles = $compostings->count();
        $activePiles = $compostings->whereNull('end_date')->count();
        $totalTrackings = Tracking::count();

        return view('admin.tracking.index', compact('compostings', 'totalPiles', 'activePiles', 'totalTrackings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener todas las pilas activas (tanto del usuario como del administrador)
        $activeCompostings = Composting::whereNull('end_date')
            ->orderBy('pile_num', 'asc')
            ->get()
            ->filter(function ($composting) {
                return $composting->status !== 'Completada';
            });

        if ($activeCompostings->isEmpty()) {
            return redirect()->route('admin.tracking.index')
                ->with('error', 'No hay pilas activas para registrar seguimiento. Primero debe crear una pila de compostaje.');
        }

        return view('admin.tracking.create', compact('activeCompostings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('=== ADMIN TRACKING STORE METHOD CALLED ===');
        \Log::info('Admin tracking store method called with data: ', $request->all());
        
        $request->validate([
            'composting_id' => 'required|exists:compostings,id',
            'day' => 'required|integer|min:1|max:45',
            'date' => 'required|date',
            'activity' => 'required|string|max:1000',
            'work_hours' => 'required|string|max:50',
            'temp_internal' => 'nullable|numeric|min:0|max:100',
            'temp_time' => 'nullable|date_format:H:i',
            'temp_env' => 'nullable|numeric|min:-10|max:50',
            'hum_pile' => 'nullable|numeric|min:0|max:100',
            'hum_env' => 'nullable|numeric|min:0|max:100',
            'ph' => 'nullable|numeric|min:0|max:14',
            'water' => 'nullable|numeric|min:0',
            'lime' => 'nullable|numeric|min:0',
            'others' => 'nullable|string|max:1000'
        ]);

        \Log::info('Validation passed successfully');

        // Verificar que la pila existe (permitir seguimientos en cualquier pila)
        \Log::info('Checking composting with ID: ' . $request->composting_id);
        $composting = Composting::where('id', $request->composting_id)
            ->first();
        
        \Log::info('Composting query completed. Found: ' . ($composting ? 'YES' : 'NO'));

        if (!$composting) {
            \Log::error('Composting not found - redirecting back');
            return redirect()->back()
                ->with('error', 'La pila de compostaje no existe.');
        }

        // Verificar si la pila ya está completada
        if ($composting->status === 'Completada') {
            \Log::info('Composting pile is already completed');
            return redirect()->back()
                ->with('error', 'Esta pila ya está completada y no se pueden agregar más seguimientos.');
        }

        \Log::info('Composting found, checking for existing tracking...');

        // Verificar que no existe un seguimiento para el mismo día
        $existingTracking = Tracking::where('composting_id', $request->composting_id)
            ->where('day', $request->day)
            ->first();

        if ($existingTracking) {
            \Log::info('Existing tracking found for day: ' . $request->day);
            return redirect()->back()
                ->withInput()
                ->with('error', "Ya existe un seguimiento para el día {$request->day} en esta pila.");
        }

        \Log::info('No existing tracking found, checking date validation...');

        // Verificar que la fecha del seguimiento no sea anterior a la fecha de inicio de la pila
        \Log::info('Request date: ' . $request->date . ', Composting start date: ' . $composting->start_date);
        $requestDate = \Carbon\Carbon::parse($request->date);
        $startDate = \Carbon\Carbon::parse($composting->start_date);
        \Log::info('Parsed request date: ' . $requestDate->format('Y-m-d') . ', Parsed start date: ' . $startDate->format('Y-m-d'));
        
        if ($requestDate->lt($startDate)) {
            \Log::info('Date validation failed - redirecting back');
            return redirect()->back()
                ->withInput()
                ->with('error', 'La fecha del seguimiento no puede ser anterior a la fecha de inicio de la pila.');
        }

        \Log::info('Date validation passed, creating tracking...');

        \Log::info('Creating tracking record...');
        $tracking = Tracking::create([
                'composting_id' => $request->composting_id,
                'day' => $request->day,
                'date' => $request->date,
                'activity' => $request->activity,
                'work_hours' => $request->work_hours,
                'temp_internal' => $request->temp_internal ?: null,
                'temp_time' => $request->temp_time ?: null,
                'temp_env' => $request->temp_env ?: null,
                'hum_pile' => $request->hum_pile ?: null,
                'hum_env' => $request->hum_env ?: null,
                'ph' => $request->ph ?: null,
                'water' => $request->water ?: null,
                'lime' => $request->lime ?: null,
                'others' => $request->others,
                'created_by' => auth()->id()
            ]);

        \Log::info('Tracking created successfully with ID: ' . $tracking->id);

        // Verificar si han pasado 45 días desde el inicio y marcar la pila como completada
        $composting = Composting::find($request->composting_id);
        $daysElapsed = $composting->days_elapsed;
        
        \Log::info("Days elapsed: {$daysElapsed}");
        
        if ($daysElapsed >= 45 && !$composting->end_date) {
            \Log::info('Process completed! Updating composting pile...');
            $composting->update([
                'end_date' => now()->toDateString()
            ]);
            \Log::info('Composting pile marked as completed with end_date: ' . now()->toDateString());
        }

        \Log::info('Redirecting to index with success message');
        return redirect()->route('admin.tracking.index')
            ->with('success', 'Seguimiento registrado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tracking $tracking)
    {
        // Permitir ver seguimientos de cualquier pila
        $tracking->load(['composting.ingredients.organic', 'composting']);
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'tracking' => [
                    'id' => $tracking->id,
                    'composting_id' => $tracking->composting_id,
                    'pile_num' => $tracking->composting->formatted_pile_num,
                    'day' => $tracking->day,
                    'formatted_day' => $tracking->formatted_day ?? 'Día ' . $tracking->day,
                    'date' => $tracking->date->format('Y-m-d'),
                    'formatted_date' => $tracking->formatted_date ?? $tracking->date->format('d/m/Y'),
                    'activity' => $tracking->activity,
                    'work_hours' => $tracking->work_hours,
                    'temp_internal' => $tracking->temp_internal,
                    'formatted_temp_internal' => $tracking->formatted_temp_internal ?? ($tracking->temp_internal ? $tracking->temp_internal . '°C' : 'N/A'),
                    'temp_time' => $tracking->temp_time,
                    'formatted_temp_time' => $tracking->formatted_temp_time ?? ($tracking->temp_time ? date('H:i', strtotime($tracking->temp_time)) : 'N/A'),
                    'temp_env' => $tracking->temp_env,
                    'formatted_temp_env' => $tracking->formatted_temp_env ?? ($tracking->temp_env ? $tracking->temp_env . '°C' : 'N/A'),
                    'hum_pile' => $tracking->hum_pile,
                    'formatted_hum_pile' => $tracking->formatted_hum_pile ?? ($tracking->hum_pile ? $tracking->hum_pile . '%' : 'N/A'),
                    'hum_env' => $tracking->hum_env,
                    'formatted_hum_env' => $tracking->formatted_hum_env ?? ($tracking->hum_env ? $tracking->hum_env . '%' : 'N/A'),
                    'ph' => $tracking->ph,
                    'formatted_ph' => $tracking->formatted_ph ?? ($tracking->ph ? $tracking->ph : 'N/A'),
                    'water' => $tracking->water,
                    'formatted_water' => $tracking->formatted_water ?? ($tracking->water ? $tracking->water . 'L' : 'N/A'),
                    'lime' => $tracking->lime,
                    'formatted_lime' => $tracking->formatted_lime ?? ($tracking->lime ? $tracking->lime . 'Kg' : 'N/A'),
                    'others' => $tracking->others,
                    'composting' => [
                        'id' => $tracking->composting->id,
                        'formatted_pile_num' => $tracking->composting->formatted_pile_num,
                        'formatted_start_date' => $tracking->composting->formatted_start_date,
                        'formatted_total_kg' => $tracking->composting->formatted_total_kg,
                        'status' => $tracking->composting->status,
                        'end_date' => $tracking->composting->end_date,
                        'ingredients' => $tracking->composting->ingredients->map(function($ingredient) {
                            return [
                                'type' => $ingredient->organic->type_in_spanish ?? 'N/A',
                                'amount' => number_format($ingredient->amount, 2) . ' Kg',
                                'notes' => $ingredient->notes
                            ];
                        })
                    ],
                    'missing_days' => $tracking->composting->missing_days
                ]
            ]);
        }
        
        return view('admin.tracking.show', compact('tracking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tracking $tracking)
    {
        // Permitir editar seguimientos de cualquier pila

        // Obtener todas las pilas activas (tanto del usuario como del administrador)
        $activeCompostings = Composting::whereNull('end_date')
            ->orderBy('pile_num', 'asc')
            ->get();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'tracking' => [
                    'id' => $tracking->id,
                    'composting_id' => $tracking->composting_id,
                    'day' => $tracking->day,
                    'date' => $tracking->date->format('Y-m-d'),
                    'activity' => $tracking->activity,
                    'work_hours' => $tracking->work_hours,
                    'temp_internal' => $tracking->temp_internal,
                    'temp_time' => $tracking->temp_time ? date('H:i', strtotime($tracking->temp_time)) : '',
                    'temp_env' => $tracking->temp_env,
                    'hum_pile' => $tracking->hum_pile,
                    'hum_env' => $tracking->hum_env,
                    'ph' => $tracking->ph,
                    'water' => $tracking->water,
                    'lime' => $tracking->lime,
                    'others' => $tracking->others
                ],
                'activeCompostings' => $activeCompostings->map(function($composting) {
                    return [
                        'id' => $composting->id,
                        'formatted_pile_num' => $composting->formatted_pile_num
                    ];
                })
            ]);
        }

        return view('admin.tracking.edit', compact('tracking', 'activeCompostings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tracking $tracking)
    {
        // Permitir editar seguimientos de cualquier pila

        $request->validate([
            'composting_id' => 'required|exists:compostings,id',
            'day' => 'required|integer|min:1|max:45',
            'date' => 'required|date',
            'activity' => 'required|string|max:1000',
            'work_hours' => 'required|string|max:50',
            'temp_internal' => 'nullable|numeric|min:0|max:100',
            'temp_time' => 'nullable|date_format:H:i',
            'temp_env' => 'nullable|numeric|min:-10|max:50',
            'hum_pile' => 'nullable|numeric|min:0|max:100',
            'hum_env' => 'nullable|numeric|min:0|max:100',
            'ph' => 'nullable|numeric|min:0|max:14',
            'water' => 'nullable|numeric|min:0',
            'lime' => 'nullable|numeric|min:0',
            'others' => 'nullable|string|max:1000'
        ]);

        \Log::info('Validation passed successfully');

        // Verificar que la pila existe (permitir seguimientos en cualquier pila)
        \Log::info('Checking composting with ID: ' . $request->composting_id);
        $composting = Composting::where('id', $request->composting_id)
            ->first();
        
        \Log::info('Composting query completed. Found: ' . ($composting ? 'YES' : 'NO'));

        if (!$composting) {
            \Log::error('Composting not found - redirecting back');
            return redirect()->back()
                ->with('error', 'La pila de compostaje no existe.');
        }

        \Log::info('Composting found, checking for existing tracking...');

        // Verificar que no existe otro seguimiento para el mismo día (excluyendo el actual)
        $existingTracking = Tracking::where('composting_id', $request->composting_id)
            ->where('day', $request->day)
            ->where('id', '!=', $tracking->id)
            ->first();

        if ($existingTracking) {
            \Log::info('Existing tracking found for day: ' . $request->day);
            return redirect()->back()
                ->withInput()
                ->with('error', "Ya existe un seguimiento para el día {$request->day} en esta pila.");
        }

        \Log::info('No existing tracking found, checking date validation...');

        // Verificar que la fecha del seguimiento no sea anterior a la fecha de inicio de la pila
        \Log::info('Request date: ' . $request->date . ', Composting start date: ' . $composting->start_date);
        $requestDate = \Carbon\Carbon::parse($request->date);
        $startDate = \Carbon\Carbon::parse($composting->start_date);
        \Log::info('Parsed request date: ' . $requestDate->format('Y-m-d') . ', Parsed start date: ' . $startDate->format('Y-m-d'));
        
        if ($requestDate->lt($startDate)) {
            \Log::info('Date validation failed - redirecting back');
            return redirect()->back()
                ->withInput()
                ->with('error', 'La fecha del seguimiento no puede ser anterior a la fecha de inicio de la pila.');
        }

        \Log::info('Date validation passed, creating tracking...');

        DB::beginTransaction();
        try {
            $tracking->update([
                'composting_id' => $request->composting_id,
                'day' => $request->day,
                'date' => $request->date,
                'activity' => $request->activity,
                'work_hours' => $request->work_hours,
                'temp_internal' => $request->temp_internal ?: null,
                'temp_time' => $request->temp_time ?: null,
                'temp_env' => $request->temp_env ?: null,
                'hum_pile' => $request->hum_pile ?: null,
                'hum_env' => $request->hum_env ?: null,
                'ph' => $request->ph ?: null,
                'water' => $request->water ?: null,
                'lime' => $request->lime ?: null,
                'others' => $request->others
            ]);

            DB::commit();

            return redirect()->route('admin.tracking.index')
                ->with('success', 'Seguimiento actualizado exitosamente!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al actualizar seguimiento: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el seguimiento: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tracking $tracking)
    {
        // Permitir eliminar seguimientos de cualquier pila

        DB::beginTransaction();
        try {
            $tracking->delete();
            
            DB::commit();
            
            return redirect()->route('admin.tracking.index')
                ->with('success', 'Seguimiento eliminado exitosamente!');
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al eliminar seguimiento: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al eliminar el seguimiento: ' . $e->getMessage());
        }
    }

    /**
     * Obtener seguimientos de una pila específica (para AJAX)
     */
    public function getByComposting(Composting $composting)
    {
        // Permitir ver seguimientos de cualquier pila (tanto del usuario como del administrador)
        
        $trackings = $composting->trackings()
            ->orderBy('day', 'asc')
            ->get()
            ->map(function($tracking) {
                return [
                    'id' => $tracking->id,
                    'composting_id' => $tracking->composting_id,
                    'day' => $tracking->day,
                    'date' => $tracking->date->format('Y-m-d'),
                    'activity' => $tracking->activity,
                    'work_hours' => $tracking->work_hours,
                    'temp_internal' => $tracking->temp_internal,
                    'temp_time' => $tracking->temp_time ? $tracking->temp_time->format('H:i') : null,
                    'temp_env' => $tracking->temp_env,
                    'hum_pile' => $tracking->hum_pile,
                    'hum_env' => $tracking->hum_env,
                    'ph' => $tracking->ph,
                    'water' => $tracking->water,
                    'lime' => $tracking->lime,
                    'others' => $tracking->others,
                    'created_by' => $tracking->created_by
                ];
            });

        // Obtener los días faltantes (días sin seguimiento registrado)
        $missingDays = $composting->missing_days;

        return response()->json([
            'composting' => [
                'id' => $composting->id,
                'pile_num' => $composting->pile_num,
                'formatted_pile_num' => $composting->formatted_pile_num,
                'start_date' => $composting->start_date,
                'end_date' => $composting->end_date,
                'status' => $composting->status,
                'trackings_count' => $trackings->count(),
                'process_progress' => $composting->process_progress,
                'current_phase' => $composting->current_phase,
                'tracking_progress' => $composting->tracking_progress,
                'is_process_completed_by_trackings' => $composting->is_process_completed_by_trackings,
                'days_elapsed' => $composting->days_elapsed
            ],
            'trackings' => $trackings,
            'missing_days' => $missingDays
        ]);
    }

    /**
     * Generate PDF for all trackings (o solo los filtrados si se pasan ids)
     */
    public function downloadAllTrackingsPDF(Request $request)
    {
        $query = Tracking::with('composting')
            ->orderBy('date', 'desc');

        if ($request->filled('ids')) {
            $ids = array_filter(array_map('intval', explode(',', $request->ids)));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        $trackings = $query->get();

        $pdf = PDF::loadView('admin.tracking.pdf.all-trackings', compact('trackings'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('todos_los_seguimientos_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate PDF for all trackings of a specific composting pile
     */
    public function downloadCompostingTrackingsPDF(Composting $composting)
    {
        $composting->load('trackings');
        $trackings = $composting->trackings()->orderBy('day', 'asc')->get();
        
        $pdf = PDF::loadView('admin.tracking.pdf.all-trackings', compact('trackings'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('seguimientos_' . str_replace(' ', '_', $composting->formatted_pile_num) . '_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate PDF for individual tracking
     */
    public function downloadTrackingPDF(Tracking $tracking)
    {
        $tracking->load('composting');
        
        // Convertir imagen de la pila a base64 si existe
        $imageBase64 = null;
        if ($tracking->composting && $tracking->composting->image && file_exists(public_path($tracking->composting->image))) {
            $imagePath = public_path($tracking->composting->image);
            $imageData = file_get_contents($imagePath);
            $imageInfo = getimagesize($imagePath);
            $mimeType = $imageInfo['mime'];
            $imageBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        }
        
        $pdf = PDF::loadView('admin.tracking.pdf.tracking-details', compact('tracking', 'imageBase64'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('seguimiento_dia_' . $tracking->day . '_pila_' . str_replace(' ', '_', $tracking->composting->formatted_pile_num) . '_' . date('Y-m-d') . '.pdf');
    }
}
