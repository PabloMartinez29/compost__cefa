<?php

// Controlador Aprendiz FertilizerController — CRUD de abono (vista aprendiz)
namespace App\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use App\Models\Fertilizer;
use App\Models\Composting;
use App\Models\Notification;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

// Abono Terminado - Aprendiz
class FertilizerController extends Controller
{
    // Listar abonos con estadísticas y estados de solicitudes de eliminación.
    public function index()
    {
        $fertilizers = Fertilizer::with('composting.creator')->orderBy('date', 'desc')->orderBy('time', 'desc')->get();
        
        $totalAmount = Fertilizer::sum('amount');
        $totalRecords = Fertilizer::count();
        $todayRecords = Fertilizer::whereDate('date', today())->count();
        $todayAmount = Fertilizer::whereDate('date', today())->sum('amount');

        $userId = auth()->check() ? auth()->id() : null;

        // Marcar notificaciones recientes como leídas
        $recentNotifications = Notification::where('from_user_id', $userId)
            ->whereIn('status', ['approved', 'rejected'])
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
        foreach ($recentNotifications as $notification) {
            $notification->update(['read_at' => now()]);
        }

        // IDs agrupados por estado para mostrar iconos en la vista
        $approvedFertilizerIds = Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')->where('status', 'approved')
            ->whereNotNull('fertilizer_id')->pluck('fertilizer_id')->toArray();

        $pendingFertilizerIds = Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')->where('status', 'pending')
            ->whereNotNull('fertilizer_id')->pluck('fertilizer_id')->toArray();

        $rejectedFertilizerIds = Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')->where('status', 'rejected')
            ->whereNotNull('fertilizer_id')->pluck('fertilizer_id')->toArray();
        
        // Mostrar vista
        return view('aprendiz.fertilizer.index', compact(
            'fertilizers', 'totalAmount', 'totalRecords', 'todayRecords', 'todayAmount',
            'recentNotifications', 'approvedFertilizerIds', 'pendingFertilizerIds', 'rejectedFertilizerIds'
        ));
    }

    // Formulario de creación. Filtra solo pilas completadas con kg disponibles.
    public function create()
    {
        $completedCompostings = Composting::with(['creator', 'trackings'])
            ->get()
            ->filter(fn($c) => $c->status === 'Completada' && ($c->total_kg ?? 0) > 0)
            ->sortByDesc(fn($c) => $c->end_date ? $c->end_date->timestamp : $c->created_at->timestamp)
            ->values();

        // Mostrar vista
        return view('aprendiz.fertilizer.create', compact('completedCompostings'));
    }

    // Crear registro. Valida pila completada y kg disponibles, resta del total_kg.
    public function store(Request $request)
    {
        // Validar datos recibidos
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

        $composting = Composting::with('trackings')->findOrFail($request->composting_id);
        
        if ($composting->status !== 'Completada') {
            // Redirigir con mensaje
            return redirect()->back()->withInput()
                ->with('error', 'La pila seleccionada no está completada.');
        }

        $availableKg = $composting->total_kg ?? 0;
        if ($request->amount > $availableKg) {
            // Redirigir con mensaje
            return redirect()->back()->withInput()
                ->with('error', "La cantidad ({$request->amount}) excede los kg disponibles ({$availableKg} Kg).");
        }

        Fertilizer::create(array_merge($request->all(), ['created_by' => auth()->id()]));

        $composting->update(['total_kg' => max(0, $availableKg - $request->amount)]);

        // Redirigir con mensaje
            return redirect()->route('aprendiz.fertilizer.index')->with('success', '¡Registro de abono creado exitosamente!');
    }

    // Ver detalle. Retorna JSON si es AJAX, vista si no.
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
        
        // Mostrar vista
        return view('aprendiz.fertilizer.show', compact('fertilizer'));
    }

    // Formulario de edición. Retorna JSON si es AJAX.
    public function edit(Fertilizer $fertilizer)
    {
        $fertilizer->load('composting');
        
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
        
        // Mostrar vista
        return view('aprendiz.fertilizer.edit', compact('fertilizer'));
    }

    // Actualizar registro. Ajusta total_kg de la pila si la cantidad cambió.
    public function update(Request $request, Fertilizer $fertilizer)
    {
        // Validar datos recibidos
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
        $amountDifference = $request->amount - $fertilizer->amount;

        if ($amountDifference != 0) {
            $currentTotalKg = $composting->total_kg ?? 0;
            
            if ($amountDifference > 0 && $amountDifference > $currentTotalKg) {
                // Redirigir con mensaje
            return redirect()->back()->withInput()
                    ->with('error', "Solo hay {$currentTotalKg} Kg disponibles en la pila.");
            }
            
            $composting->update(['total_kg' => max(0, $currentTotalKg - $amountDifference)]);
        }

        $fertilizer->update($request->all());

        // Redirigir con mensaje
            return redirect()->route('aprendiz.fertilizer.index')->with('success', '¡Registro de abono actualizado exitosamente!');
    }

    // Eliminar registro. Requiere: ser el creador + tener solicitud aprobada.
    public function destroy(Fertilizer $fertilizer)
    {
        $currentUserId = auth()->check() ? auth()->id() : null;
        
        if ($fertilizer->created_by !== $currentUserId) {
            // Redirigir con mensaje
            return redirect()->route('aprendiz.fertilizer.index')
                ->with('permission_required', 'No tiene permisos para eliminar este registro.');
        }
        
        $approvedNotification = Notification::where('from_user_id', $currentUserId)
            ->where('fertilizer_id', $fertilizer->id)
            ->where('type', 'delete_request')
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->first();
        
        if (!$approvedNotification) {
            // Redirigir con mensaje
            return redirect()->route('aprendiz.fertilizer.index')
                ->with('permission_required', 'Debe solicitar permiso primero y esperar aprobación del administrador.');
        }

        $approvedNotification->update(['read_at' => now()]);
        $fertilizer->delete();

        // Redirigir con mensaje
            return redirect()->route('aprendiz.fertilizer.index')->with('success', '¡Registro de abono eliminado exitosamente!');
    }

    // Solicitar permiso al admin para eliminar. Evita duplicados.
    public function requestDeletePermission(Fertilizer $fertilizer)
    {
        $currentUserId = auth()->check() ? auth()->id() : null;
        
        if ($fertilizer->created_by !== $currentUserId) {
            // Redirigir con mensaje
            return redirect()->route('aprendiz.fertilizer.index')
                ->with('permission_required', 'No puede solicitar permisos para registros que no le pertenecen.');
        }

        $existing = Notification::where('from_user_id', $currentUserId)
            ->where('fertilizer_id', $fertilizer->id)
            ->where('type', 'delete_request')
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
        if ($existing && $existing->status === 'pending') {
            // Redirigir con mensaje
            return redirect()->route('aprendiz.fertilizer.index')
                ->with('permission_required', 'Su solicitud ya está pendiente de aprobación.');
        }
        if ($existing && $existing->status === 'approved') {
            // Redirigir con mensaje
            return redirect()->route('aprendiz.fertilizer.index')
                ->with('success', 'Su solicitud ya fue aprobada. Ahora puede eliminar el registro.');
        }

        // Eliminar rechazadas anteriores para permitir reintento
        Notification::where('from_user_id', $currentUserId)
            ->where('fertilizer_id', $fertilizer->id)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->delete();

        // Notificar a todos los admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'from_user_id' => $currentUserId,
                'fertilizer_id' => $fertilizer->id,
                'type' => 'delete_request',
                'status' => 'pending',
                'message' => auth()->user()->name . ' solicita eliminar abono #' . str_pad($fertilizer->id, 3, '0', STR_PAD_LEFT)
            ]);
        }

        // Redirigir con mensaje
            return redirect()->route('aprendiz.fertilizer.index')
            ->with('success', 'Solicitud de eliminación enviada al administrador.');
    }

    // PDF general. Acepta ?ids=1,2,3 para filtrar desde DataTables.
    public function downloadAllFertilizersPDF(Request $request)
    {
        $query = Fertilizer::with('composting')->orderBy('date', 'desc')->orderBy('time', 'desc');

        if ($request->filled('ids')) {
            $ids = array_filter(array_map('intval', explode(',', $request->ids)));
            if (!empty($ids)) $query->whereIn('id', $ids);
        }

        $pdf = PDF::loadView('aprendiz.fertilizer.pdf.all-fertilizers', ['fertilizers' => $query->get()])
            ->setPaper('a4', 'landscape')
            ->setOptions(['defaultFont' => 'Arial', 'isRemoteEnabled' => false, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => false]);
        
        return $pdf->download('todos_los_abonos_' . date('Y-m-d') . '.pdf');
    }

    // PDF individual con detalle completo.
    public function downloadFertilizerPDF(Fertilizer $fertilizer)
    {
        $fertilizer->load('composting.creator');
        
        $pdf = PDF::loadView('aprendiz.fertilizer.pdf.fertilizer-details', compact('fertilizer'))
            ->setPaper('a4', 'portrait')
            ->setOptions(['defaultFont' => 'Arial', 'isRemoteEnabled' => false, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => false]);
        
        return $pdf->download('abono_' . str_pad($fertilizer->id, 3, '0', STR_PAD_LEFT) . '_' . date('Y-m-d') . '.pdf');
    }
}
