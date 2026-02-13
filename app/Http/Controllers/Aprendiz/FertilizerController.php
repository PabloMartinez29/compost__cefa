<?php

namespace App\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use App\Models\Fertilizer;
use App\Models\Composting;
use App\Models\Notification;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class FertilizerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fertilizers = Fertilizer::with('composting.creator')->orderBy('date', 'desc')->orderBy('time', 'desc')->get();
        
        // Statistics
        $totalAmount = Fertilizer::sum('amount');
        $totalRecords = Fertilizer::count();
        $todayRecords = Fertilizer::whereDate('date', today())->count();
        $todayAmount = Fertilizer::whereDate('date', today())->sum('amount');

        // Verificar notificaciones recientes
        $userId = auth()->check() ? auth()->id() : null;
        $recentNotifications = Notification::where('user_id', $userId)
            ->whereIn('status', ['approved', 'rejected'])
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();

        // Marcar las no leídas como leídas al mostrarlas
        foreach ($recentNotifications as $notification) {
            $notification->update(['read_at' => now()]);
        }

        // IDs de abonos con aprobación vigente para eliminar
        $approvedFertilizerIds = Notification::where('user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'approved')
            ->whereNotNull('fertilizer_id')
            ->pluck('fertilizer_id')
            ->toArray();

        // IDs de abonos con solicitud pendiente
        $pendingFertilizerIds = Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'pending')
            ->whereNotNull('fertilizer_id')
            ->pluck('fertilizer_id')
            ->toArray();

        // IDs de abonos con solicitud rechazada
        $rejectedFertilizerIds = Notification::where('user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->whereNotNull('fertilizer_id')
            ->pluck('fertilizer_id')
            ->toArray();
        
        // También verificar notificaciones pendientes que fueron rechazadas
        $rejectedFromPending = Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->whereNotNull('fertilizer_id')
            ->pluck('fertilizer_id')
            ->toArray();
        
        $rejectedFertilizerIds = array_unique(array_merge($rejectedFertilizerIds, $rejectedFromPending));
        
        return view('aprendiz.fertilizer.index', compact(
            'fertilizers',
            'totalAmount',
            'totalRecords',
            'todayRecords',
            'todayAmount',
            'recentNotifications',
            'approvedFertilizerIds',
            'pendingFertilizerIds',
            'rejectedFertilizerIds'
        ));
    }

    /**
     * Show the form for creating a new resource.
     * Pilas completadas con kg disponibles (total_kg = saldo restante); se permiten varias ventas por pila.
     */
    public function create()
    {
        $completedCompostings = Composting::with(['creator', 'trackings'])
            ->get()
            ->filter(function($composting) {
                return $composting->status === 'Completada' && ($composting->total_kg ?? 0) > 0;
            })
            ->sortByDesc(function($composting) {
                return $composting->end_date ? $composting->end_date->timestamp : $composting->created_at->timestamp;
            })
            ->values();

        return view('aprendiz.fertilizer.create', compact('completedCompostings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'composting_id' => 'required|exists:compostings,id',
            'date' => 'required|date',
            'time' => 'required',
            'requester' => 'required|string|max:150',
            'destination' => 'required|string|max:150',
            'received_by' => 'required|string|max:150',
            'delivered_by' => 'required|string|max:150',
            'type' => 'required|in:Liquid,Solid',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string'
        ]);

        // Verificar que la pila esté completada
        $composting = Composting::with('trackings')->findOrFail($request->composting_id);
        
        // Usar el accessor status para mantener la misma lógica que en create()
        if ($composting->status !== 'Completada') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'La pila seleccionada no está completada. Solo se pueden registrar abonos de pilas completadas.');
        }

        // total_kg en la pila es el saldo restante; se actualiza en cada venta
        $availableKg = $composting->total_kg ?? 0;
        if ($request->amount > $availableKg) {
            return redirect()->back()
                ->withInput()
                ->with('error', "La cantidad solicitada ({$request->amount} " . ($request->type === 'Liquid' ? 'L' : 'Kg') . ") excede los kilogramos beneficiados disponibles ({$availableKg} Kg) de la pila.");
        }

        // Crear el registro de abono
        Fertilizer::create($request->all());

        // Restar la cantidad de los kilogramos beneficiados de la pila
        $newTotalKg = max(0, $availableKg - $request->amount);
        $composting->update(['total_kg' => $newTotalKg]);

        return redirect()->route('aprendiz.fertilizer.index')->with('success', '¡Registro de abono creado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fertilizer $fertilizer)
    {
        $fertilizer->load('composting.creator');
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'id' => $fertilizer->id,
                'formatted_date' => $fertilizer->formatted_date,
                'time' => $fertilizer->time,
                'composting' => $fertilizer->composting ? [
                    'id' => $fertilizer->composting->id,
                    'formatted_pile_num' => $fertilizer->composting->formatted_pile_num,
                ] : null,
                'type' => $fertilizer->type,
                'type_in_spanish' => $fertilizer->type_in_spanish,
                'formatted_amount' => $fertilizer->formatted_amount,
                'amount' => $fertilizer->amount,
                'requester' => $fertilizer->requester,
                'destination' => $fertilizer->destination,
                'received_by' => $fertilizer->received_by,
                'delivered_by' => $fertilizer->delivered_by,
                'notes' => $fertilizer->notes,
            ]);
        }
        
        return view('aprendiz.fertilizer.show', compact('fertilizer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fertilizer $fertilizer)
    {
        $fertilizer->load('composting');
        
        // Si es una petición AJAX, devolver JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'fertilizer' => [
                    'id' => $fertilizer->id,
                    'date' => $fertilizer->date->format('Y-m-d'),
                    'time' => $fertilizer->time,
                    'composting_id' => $fertilizer->composting_id,
                    'composting' => $fertilizer->composting ? [
                        'id' => $fertilizer->composting->id,
                        'formatted_pile_num' => $fertilizer->composting->formatted_pile_num,
                    ] : null,
                    'requester' => $fertilizer->requester,
                    'destination' => $fertilizer->destination,
                    'received_by' => $fertilizer->received_by,
                    'delivered_by' => $fertilizer->delivered_by,
                    'type' => $fertilizer->type,
                    'amount' => $fertilizer->amount,
                    'notes' => $fertilizer->notes,
                ]
            ]);
        }
        
        return view('aprendiz.fertilizer.edit', compact('fertilizer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fertilizer $fertilizer)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'requester' => 'required|string|max:150',
            'destination' => 'required|string|max:150',
            'received_by' => 'required|string|max:150',
            'delivered_by' => 'required|string|max:150',
            'type' => 'required|in:Liquid,Solid',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string'
        ]);

        $composting = $fertilizer->composting;
        $oldAmount = $fertilizer->amount;
        $newAmount = $request->amount;
        $amountDifference = $newAmount - $oldAmount;

        // Si la cantidad cambió, actualizar los kilogramos beneficiados de la pila
        if ($amountDifference != 0) {
            $currentTotalKg = $composting->total_kg ?? 0;
            
            // Si se aumenta la cantidad, validar que no exceda lo disponible
            if ($amountDifference > 0) {
                $availableKg = $currentTotalKg;
                if ($amountDifference > $availableKg) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "No se puede aumentar la cantidad. Solo hay {$availableKg} Kg disponibles en la pila.");
                }
            }
            
            // Calcular el nuevo total de kilogramos beneficiados
            // Si se aumenta: restar la diferencia
            // Si se disminuye: sumar la diferencia (devolver a la pila)
            $newTotalKg = max(0, $currentTotalKg - $amountDifference);
            $composting->update(['total_kg' => $newTotalKg]);
        }

        $fertilizer->update($request->all());

        return redirect()->route('aprendiz.fertilizer.index')->with('success', '¡Registro de abono actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fertilizer $fertilizer)
    {
        $currentUserId = auth()->check() ? auth()->id() : null;
        
        // Verificar que el registro pertenece al usuario (a través del composting)
        $fertilizer->load('composting');
        if ($fertilizer->composting && $fertilizer->composting->created_by !== $currentUserId) {
            return redirect()->route('aprendiz.fertilizer.index')
                ->with('permission_required', 'No tiene permisos para eliminar este registro.');
        }
        
        // Verificar que hay una solicitud aprobada
        $approvedNotification = Notification::where('user_id', $currentUserId)
            ->where('fertilizer_id', $fertilizer->id)
            ->where('type', 'delete_request')
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->first();
        
        if (!$approvedNotification) {
            return redirect()->route('aprendiz.fertilizer.index')
                ->with('permission_required', 'No tiene permiso para eliminar este registro. Debe solicitar permiso primero y esperar la aprobación del administrador.');
        }

        // Marcar la notificación como procesada
        $approvedNotification->update(['read_at' => now()]);

        // Importante: al eliminar un registro de abono NO se deben
        // devolver los kilogramos beneficiados a la pila, para evitar
        // que se altere el historial real de salida de abono.

        $fertilizer->delete();

        return redirect()->route('aprendiz.fertilizer.index')->with('success', '¡Registro de abono eliminado exitosamente!');
    }

    /**
     * Solicitar permiso para eliminar un registro
     */
    public function requestDeletePermission(Fertilizer $fertilizer)
    {
        // Verificar que el registro pertenece al usuario (a través del composting)
        $currentUserId = auth()->check() ? auth()->id() : null;
        $fertilizer->load('composting');
        
        if (!$fertilizer->composting || $fertilizer->composting->created_by !== $currentUserId) {
            return redirect()->route('aprendiz.fertilizer.index')
                ->with('permission_required', 'No puede solicitar permisos para registros que no le pertenecen.');
        }

        // Evitar solicitudes duplicadas si ya hay una pendiente o aprobada
        $existing = Notification::where('from_user_id', $currentUserId)
            ->where('fertilizer_id', $fertilizer->id)
            ->where('type', 'delete_request')
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
        if ($existing && $existing->status === 'pending') {
            return redirect()->route('aprendiz.fertilizer.index')
                ->with('permission_required', 'Su solicitud de eliminación ya está pendiente de aprobación del administrador.');
        }
        
        if ($existing && $existing->status === 'approved') {
            return redirect()->route('aprendiz.fertilizer.index')
                ->with('success', 'Su solicitud ya fue aprobada. Ahora puede eliminar el registro.');
        }

        // Si hay una solicitud rechazada, eliminarla para permitir nueva solicitud
        $rejected = Notification::where('from_user_id', $currentUserId)
            ->where('fertilizer_id', $fertilizer->id)
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
                // Crear notificación para cada administrador
                Notification::create([
                    'user_id' => $admin->id,
                    'from_user_id' => $currentUserId,
                    'fertilizer_id' => $fertilizer->id,
                    'type' => 'delete_request',
                    'status' => 'pending',
                    'message' => (auth()->check() ? auth()->user()->name : 'Usuario') . ' solicita permiso para eliminar el registro de abono #' . str_pad($fertilizer->id, 3, '0', STR_PAD_LEFT)
                ]);
            }
        }

        return redirect()->route('aprendiz.fertilizer.index')
            ->with('success', 'Solicitud de eliminación enviada al administrador. Recibirá una notificación cuando sea aprobada.');
    }

    /**
     * Generate PDF for all fertilizers (o solo los filtrados si se pasan ids)
     */
    public function downloadAllFertilizersPDF(Request $request)
    {
        $query = Fertilizer::with('composting')->orderBy('date', 'desc')->orderBy('time', 'desc');

        if ($request->filled('ids')) {
            $ids = array_filter(array_map('intval', explode(',', $request->ids)));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        $fertilizers = $query->get();

        $pdf = PDF::loadView('aprendiz.fertilizer.pdf.all-fertilizers', compact('fertilizers'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('todos_los_abonos_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate PDF for individual fertilizer
     */
    public function downloadFertilizerPDF(Fertilizer $fertilizer)
    {
        $fertilizer->load('composting.creator');
        
        $pdf = PDF::loadView('aprendiz.fertilizer.pdf.fertilizer-details', compact('fertilizer'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false,
            ]);
        
        return $pdf->download('abono_' . str_pad($fertilizer->id, 3, '0', STR_PAD_LEFT) . '_' . date('Y-m-d') . '.pdf');
    }
}

