<?php

namespace App\Http\Controllers\Aprendiz;

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

        // IDs de seguimientos con aprobación vigente para eliminar
        $userId = auth()->check() ? auth()->id() : null;
        $approvedTrackingIds = \App\Models\Notification::where('user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'approved')
            ->whereNotNull('tracking_id')
            ->pluck('tracking_id')
            ->toArray();

        // IDs de seguimientos con solicitud pendiente
        $pendingTrackingIds = \App\Models\Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'pending')
            ->whereNotNull('tracking_id')
            ->pluck('tracking_id')
            ->toArray();

        // IDs de seguimientos con solicitud rechazada
        $rejectedTrackingIds = \App\Models\Notification::where('user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->whereNotNull('tracking_id')
            ->pluck('tracking_id')
            ->toArray();
        
        // También verificar notificaciones pendientes que fueron rechazadas
        $rejectedFromPending = \App\Models\Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->whereNotNull('tracking_id')
            ->pluck('tracking_id')
            ->toArray();
        
        $rejectedTrackingIds = array_unique(array_merge($rejectedTrackingIds, $rejectedFromPending));

        return view('aprendiz.tracking.index', compact(
            'compostings', 
            'totalPiles', 
            'activePiles', 
            'totalTrackings',
            'approvedTrackingIds',
            'pendingTrackingIds',
            'rejectedTrackingIds'
        ));
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
            return redirect()->route('aprendiz.tracking.index')
                ->with('error', 'No hay pilas activas para registrar seguimiento. Primero debe crear una pila de compostaje.');
        }

        return view('aprendiz.tracking.create', compact('activeCompostings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('=== TRACKING STORE METHOD CALLED ===');
        \Log::info('Tracking store method called with data: ', $request->all());
        
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
        return redirect()->route('aprendiz.tracking.index')
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
        
        return view('aprendiz.tracking.show', compact('tracking'));
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

        return view('aprendiz.tracking.edit', compact('tracking', 'activeCompostings'));
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

            return redirect()->route('aprendiz.tracking.index')
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
        $currentUserId = auth()->check() ? auth()->id() : null;
        
        // Cargar la relación con composting y su creador
        $tracking->load(['composting.creator']);
        
        // Verificar que el registro pertenece al usuario
        $isOwner = $tracking->created_by === $currentUserId;
        
        // Verificar si la pila fue creada por un admin
        $isPileCreatedByAdmin = $tracking->composting && $tracking->composting->creator && $tracking->composting->creator->role === 'admin';
        
        // Si el seguimiento pertenece al aprendiz pero la pila fue creada por admin, necesita permiso
        // Si el seguimiento no pertenece al aprendiz, no puede eliminarlo
        if (!$isOwner) {
            return redirect()->route('aprendiz.tracking.index')
                ->with('permission_required', 'No tiene permisos para eliminar este registro.');
        }
        
        // Si la pila fue creada por admin, siempre necesita permiso aprobado
        if ($isPileCreatedByAdmin) {
            // Verificar que hay una solicitud aprobada
            $approvedNotification = \App\Models\Notification::where('user_id', $currentUserId)
                ->where('tracking_id', $tracking->id)
                ->where('type', 'delete_request')
                ->where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->first();
            
            if (!$approvedNotification) {
                return redirect()->route('aprendiz.tracking.index')
                    ->with('permission_required', 'No tiene permiso para eliminar este seguimiento. Debe solicitar permiso primero y esperar la aprobación del administrador.');
            }
            
            // Marcar la notificación como procesada
            $approvedNotification->update(['read_at' => now()]);
        }

        DB::beginTransaction();
        try {
            $tracking->delete();
            
            DB::commit();
            
            return redirect()->route('aprendiz.tracking.index')
                ->with('success', 'Seguimiento eliminado exitosamente!');
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al eliminar seguimiento: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al eliminar el seguimiento: ' . $e->getMessage());
        }
    }

    /**
     * Solicitar permiso para eliminar un seguimiento
     */
    public function requestDeletePermission(Tracking $tracking)
    {
        $currentUserId = auth()->check() ? auth()->id() : null;
        
        // Cargar la relación con composting y su creador
        $tracking->load(['composting.creator']);
        
        // Verificar que el registro pertenece al usuario
        $isOwner = $tracking->created_by === $currentUserId;
        
        // Verificar si la pila fue creada por un admin
        $isPileCreatedByAdmin = $tracking->composting && $tracking->composting->creator && $tracking->composting->creator->role === 'admin';
        
        // Solo puede solicitar permiso si es el dueño del seguimiento
        // Y si la pila fue creada por admin, siempre necesita permiso
        if (!$isOwner) {
            return redirect()->route('aprendiz.tracking.index')
                ->with('permission_required', 'No puede solicitar permisos para registros que no le pertenecen.');
        }
        
        // Si la pila no fue creada por admin y el seguimiento es del aprendiz, puede eliminarlo directamente
        // Pero si la pila fue creada por admin, siempre necesita permiso
        if (!$isPileCreatedByAdmin) {
            return redirect()->route('aprendiz.tracking.index')
                ->with('info', 'Puede eliminar este seguimiento directamente ya que la pila no fue creada por un administrador.');
        }

        // Evitar solicitudes duplicadas si ya hay una pendiente o aprobada
        $existing = \App\Models\Notification::where('from_user_id', $currentUserId)
            ->where('tracking_id', $tracking->id)
            ->where('type', 'delete_request')
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
        if ($existing && $existing->status === 'pending') {
            return redirect()->route('aprendiz.tracking.index')
                ->with('permission_required', 'Su solicitud de eliminación ya está pendiente de aprobación del administrador.');
        }
        
        if ($existing && $existing->status === 'approved') {
            return redirect()->route('aprendiz.tracking.index')
                ->with('success', 'Su solicitud ya fue aprobada. Ahora puede eliminar el registro.');
        }

        // Si hay una solicitud rechazada, eliminarla para permitir nueva solicitud
        $rejected = \App\Models\Notification::where('from_user_id', $currentUserId)
            ->where('tracking_id', $tracking->id)
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
                \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'from_user_id' => $currentUserId,
                    'tracking_id' => $tracking->id,
                    'type' => 'delete_request',
                    'status' => 'pending',
                    'message' => (auth()->check() ? auth()->user()->name : 'Usuario') . ' solicita permiso para eliminar el seguimiento Día ' . $tracking->day . ' de la pila ' . $tracking->composting->formatted_pile_num
                ]);
            }
        }

        return redirect()->route('aprendiz.tracking.index')
            ->with('success', 'Solicitud de eliminación enviada al administrador. Recibirá una notificación cuando sea aprobada.');
    }

    /**
     * Obtener seguimientos de una pila específica (para AJAX)
     */
    public function getByComposting(Composting $composting)
    {
        // Permitir ver seguimientos de cualquier pila (tanto del usuario como del administrador)
        
        // Cargar la relación con el creador de la pila
        $composting->load('creator');
        $isPileCreatedByAdmin = $composting->creator && $composting->creator->role === 'admin';
        
        $trackings = $composting->trackings()
            ->orderBy('day', 'asc')
            ->get()
            ->map(function($tracking) use ($isPileCreatedByAdmin) {
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
                    'created_by' => $tracking->created_by,
                    'pile_created_by_admin' => $isPileCreatedByAdmin
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
     * Generate PDF for all trackings
     */
    public function downloadAllTrackingsPDF()
    {
        $trackings = Tracking::with('composting')
            ->orderBy('date', 'desc')
            ->get();
        
        $pdf = PDF::loadView('aprendiz.tracking.pdf.all-trackings', compact('trackings'))
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
        
        $pdf = PDF::loadView('aprendiz.tracking.pdf.all-trackings', compact('trackings'))
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
        if ($tracking->composting && $tracking->composting->image && Storage::disk('public')->exists($tracking->composting->image)) {
            $imagePath = Storage::disk('public')->path($tracking->composting->image);
            $imageData = file_get_contents($imagePath);
            $imageInfo = getimagesize($imagePath);
            $mimeType = $imageInfo['mime'];
            $imageBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        }
        
        $pdf = PDF::loadView('aprendiz.tracking.pdf.tracking-details', compact('tracking', 'imageBase64'))
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