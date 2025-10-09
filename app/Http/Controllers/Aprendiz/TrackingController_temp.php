<?php

namespace App\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use App\Models\Composting;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        // Obtener todas las pilas de compostaje (temporalmente todas para testing)
        $compostings = Composting::with(['trackings' => function($query) {
                $query->orderBy('day', 'asc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calcular estadísticas
        $totalPiles = $compostings->count();
        $activePiles = $compostings->whereNull('end_date')->count();
        $totalTrackings = $compostings->sum(function($composting) {
            return $composting->trackings->count();
        });

        return view('aprendiz.tracking.index', compact('compostings', 'totalPiles', 'activePiles', 'totalTrackings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener pilas activas (temporalmente todas para testing)
        $activeCompostings = Composting::whereNull('end_date')
            ->orderBy('pile_num', 'asc')
            ->get();

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
        \Log::info('Store method called with data: ', $request->all());
        
        $request->validate([
            'composting_id' => 'required|exists:compostings,id',
            'day' => 'required|integer|min:1|max:45',
            'date' => 'required|date',
            'activity' => 'required|string|max:1000',
            'work_hours' => 'required|string|max:50',
            'temp_internal' => 'required|numeric|min:0|max:100',
            'temp_time' => 'required|date_format:H:i',
            'temp_env' => 'required|numeric|min:-10|max:50',
            'hum_pile' => 'required|numeric|min:0|max:100',
            'hum_env' => 'required|numeric|min:0|max:100',
            'ph' => 'required|numeric|min:0|max:14',
            'water' => 'required|numeric|min:0',
            'lime' => 'required|numeric|min:0',
            'others' => 'nullable|string|max:1000'
        ]);

        // Verificar que la pila existe (temporalmente sin restricción de usuario)
        $composting = Composting::where('id', $request->composting_id)->first();
        \Log::info('Composting found: ', ['id' => $composting ? $composting->id : 'null']);

        if (!$composting) {
            \Log::error('Composting not found for ID: ' . $request->composting_id);
            return redirect()->back()
                ->with('error', 'Pila de compostaje no encontrada.');
        }

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

        DB::beginTransaction();
        try {
            \Log::info('Creating tracking...');
            
            $tracking = Tracking::create([
                'composting_id' => $request->composting_id,
                'day' => $request->day,
                'date' => $request->date,
                'activity' => $request->activity,
                'work_hours' => $request->work_hours,
                'temp_internal' => $request->temp_internal,
                'temp_time' => $request->temp_time,
                'temp_env' => $request->temp_env,
                'hum_pile' => $request->hum_pile,
                'hum_env' => $request->hum_env,
                'ph' => $request->ph,
                'water' => $request->water,
                'lime' => $request->lime,
                'others' => $request->others
            ]);

            \Log::info('Tracking created successfully with ID: ' . $tracking->id);
            DB::commit();

            return redirect()->route('aprendiz.tracking.index')
                ->with('success', 'Seguimiento registrado exitosamente!');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error al crear seguimiento: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar el seguimiento: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tracking $tracking)
    {
        // Verificar que el seguimiento pertenece a una pila del aprendiz
        $composting = $tracking->composting;
        if ($composting->created_by !== auth()->id()) {
            abort(403, 'No tiene permisos para ver este seguimiento.');
        }

        return view('aprendiz.tracking.show', compact('tracking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tracking $tracking)
    {
        // Verificar que el seguimiento pertenece a una pila del aprendiz
        $composting = $tracking->composting;
        if ($composting->created_by !== auth()->id()) {
            abort(403, 'No tiene permisos para editar este seguimiento.');
        }

        // Obtener pilas activas del aprendiz
        $activeCompostings = Composting::where('created_by', auth()->id())
            ->whereNull('end_date')
            ->orderBy('pile_num', 'asc')
            ->get();

        return view('aprendiz.tracking.edit', compact('tracking', 'activeCompostings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tracking $tracking)
    {
        // Verificar que el seguimiento pertenece a una pila del aprendiz
        $composting = $tracking->composting;
        if ($composting->created_by !== auth()->id()) {
            abort(403, 'No tiene permisos para editar este seguimiento.');
        }

        $request->validate([
            'composting_id' => 'required|exists:compostings,id',
            'day' => 'required|integer|min:1|max:45',
            'date' => 'required|date',
            'activity' => 'required|string|max:1000',
            'work_hours' => 'required|string|max:50',
            'temp_internal' => 'required|numeric|min:0|max:100',
            'temp_time' => 'required|date_format:H:i',
            'temp_env' => 'required|numeric|min:-10|max:50',
            'hum_pile' => 'required|numeric|min:0|max:100',
            'hum_env' => 'required|numeric|min:0|max:100',
            'ph' => 'required|numeric|min:0|max:14',
            'water' => 'required|numeric|min:0',
            'lime' => 'required|numeric|min:0',
            'others' => 'nullable|string|max:1000'
        ]);

        // Verificar que la pila pertenece al aprendiz
        $composting = Composting::where('id', $request->composting_id)
            ->where('created_by', auth()->id())
            ->first();

        if (!$composting) {
            return redirect()->back()
                ->with('error', 'No tiene permisos para registrar seguimiento en esta pila.');
        }

        // Verificar que no existe otro seguimiento para el mismo día (excluyendo el actual)
        $existingTracking = Tracking::where('composting_id', $request->composting_id)
            ->where('day', $request->day)
            ->where('id', '!=', $tracking->id)
            ->first();

        if ($existingTracking) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Ya existe un seguimiento para el día {$request->day} en esta pila.");
        }

        DB::beginTransaction();
        try {
            $tracking->update([
                'composting_id' => $request->composting_id,
                'day' => $request->day,
                'date' => $request->date,
                'activity' => $request->activity,
                'work_hours' => $request->work_hours,
                'temp_internal' => $request->temp_internal,
                'temp_time' => $request->temp_time,
                'temp_env' => $request->temp_env,
                'hum_pile' => $request->hum_pile,
                'hum_env' => $request->hum_env,
                'ph' => $request->ph,
                'water' => $request->water,
                'lime' => $request->lime,
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
        // Verificar que el seguimiento pertenece a una pila del aprendiz
        $composting = $tracking->composting;
        if ($composting->created_by !== auth()->id()) {
            abort(403, 'No tiene permisos para eliminar este seguimiento.');
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
     * Obtener seguimientos de una pila específica (para AJAX)
     */
    public function getByComposting(Composting $composting)
    {
        // Verificar que la pila pertenece al aprendiz
        if ($composting->created_by !== auth()->id()) {
            abort(403, 'No tiene permisos para ver los seguimientos de esta pila.');
        }

        $trackings = $composting->trackings()
            ->orderBy('day', 'asc')
            ->get();

        return response()->json([
            'composting' => $composting,
            'trackings' => $trackings
        ]);
    }
}
