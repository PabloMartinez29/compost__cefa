<?php

namespace App\Http\Controllers\Aprendiz;

use App\Http\Controllers\Controller;
use App\Models\Organic;
use App\Models\WarehouseClassification;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organics = Organic::orderBy('created_at', 'desc')->paginate(10);
        
        // Calcular estadísticas
        $totalWeight = Organic::sum('weight');
        $totalRecords = Organic::count();
        $todayRecords = Organic::whereDate('created_at', today())->count();
        $todayWeight = Organic::whereDate('created_at', today())->sum('weight');

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

        // IDs de orgánicos con aprobación vigente para eliminar
        $approvedOrganicIds = Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'approved')
            ->pluck('organic_id')
            ->toArray();

        // IDs de orgánicos con solicitud pendiente
        $pendingOrganicIds = Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'pending')
            ->pluck('organic_id')
            ->toArray();

        // IDs de orgánicos con solicitud rechazada
        $rejectedOrganicIds = Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->pluck('organic_id')
            ->toArray();
        
        return view('aprendiz.organic.index', compact(
            'organics',
            'totalWeight',
            'totalRecords',
            'todayRecords',
            'todayWeight',
            'recentNotifications',
            'approvedOrganicIds',
            'pendingOrganicIds',
            'rejectedOrganicIds'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('aprendiz.organic.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:Kitchen,Beds,Leaves,CowDung,ChickenManure,PigManure,Other',
            'weight' => 'required|numeric|min:0.01',
            'delivered_by' => 'required|string|max:100',
            'received_by' => 'required|string|max:100',
            'notes' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('organics', 'public');
        }

        // Agregar el ID del usuario que crea el registro
        $data['created_by'] = auth()->check() ? auth()->id() : null;

        $organic = Organic::create($data);

        // Crear movimiento automático en bodega de clasificación
        WarehouseClassification::create([
            'date' => $data['date'],
            'type' => $data['type'],
            'movement_type' => 'entry', // Entrada automática
            'weight' => $data['weight'],
            'notes' => 'Entrada automática desde registro de residuos orgánicos',
            'processed_by' => $data['received_by'],
            'img' => $data['img'] // Misma imagen si existe
        ]);

        return redirect()->route('aprendiz.organic.index')->with('success', 'Residuo orgánico registrado y clasificado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Organic $organic)
    {
        return view('aprendiz.organic.show', compact('organic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organic $organic)
    {
        return view('aprendiz.organic.edit', compact('organic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organic $organic)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:Kitchen,Beds,Leaves,CowDung,ChickenManure,PigManure,Other',
            'weight' => 'required|numeric|min:0.01',
            'delivered_by' => 'required|string|max:100',
            'received_by' => 'required|string|max:100',
            'notes' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('img')) {
            // Delete old image
            if ($organic->img) {
                Storage::disk('public')->delete($organic->img);
            }
            $data['img'] = $request->file('img')->store('organics', 'public');
        }

        $organic->update($data);

        return redirect()->route('aprendiz.organic.index')->with('success', 'Residuo orgánico actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organic $organic)
    {
        // Restar del inventario de bodega antes de eliminar
        WarehouseClassification::create([
            'date' => now()->toDateString(),
            'type' => $organic->type,
            'movement_type' => 'exit',
            'weight' => $organic->weight,
            'notes' => "Eliminación de residuo orgánico #" . str_pad($organic->id, 3, '0', STR_PAD_LEFT),
            'processed_by' => auth()->user()->name
        ]);

        // Delete image if exists
        if ($organic->img) {
            Storage::disk('public')->delete($organic->img);
        }

        $organic->delete();

        return redirect()->route('aprendiz.organic.index')->with('success', 'Residuo orgánico eliminado exitosamente! Las cantidades han sido restadas del inventario.');
    }

    /**
     * Solicitar permiso para editar un registro
     */
    public function requestEditPermission(Organic $organic)
    {
        // Verificar que el registro pertenece al usuario
        $currentUserId = auth()->check() ? auth()->id() : null;
        if ($organic->created_by !== $currentUserId) {
            return redirect()->route('aprendiz.organic.index')
                ->with('permission_required', 'No puede solicitar permisos para registros que no le pertenecen.');
        }

        // Aquí se implementaría la lógica para enviar notificación al administrador
        // Por ahora, solo mostramos un mensaje
        return redirect()->route('aprendiz.organic.index')
            ->with('success', 'Solicitud de edición enviada al administrador. Recibirá una notificación cuando sea aprobada.');
    }

    /**
     * Solicitar permiso para eliminar un registro
     */
    public function requestDeletePermission(Organic $organic)
    {
        // Verificar que el registro pertenece al usuario
        $currentUserId = auth()->check() ? auth()->id() : null;
        if ($organic->created_by !== $currentUserId) {
            return redirect()->route('aprendiz.organic.index')
                ->with('permission_required', 'No puede solicitar permisos para registros que no le pertenecen.');
        }

        // Evitar solicitudes duplicadas si ya hay una pendiente o aprobada
        $currentUserId = auth()->check() ? auth()->id() : null;
        $existing = Notification::where('from_user_id', $currentUserId)
            ->where('organic_id', $organic->id)
            ->where('type', 'delete_request')
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
        if ($existing && $existing->status === 'pending') {
            return redirect()->route('aprendiz.organic.index')
                ->with('permission_required', 'Su solicitud de eliminación ya está pendiente de aprobación del administrador.');
        }
        
        if ($existing && $existing->status === 'approved') {
            return redirect()->route('aprendiz.organic.index')
                ->with('success', 'Su solicitud ya fue aprobada. Ahora puede eliminar el registro.');
        }

        // Si hay una solicitud rechazada, eliminarla para permitir nueva solicitud
        $rejected = Notification::where('from_user_id', $currentUserId)
            ->where('organic_id', $organic->id)
            ->where('type', 'delete_request')
            ->where('status', 'rejected')
            ->first();
        
        if ($rejected) {
            $rejected->delete();
        }

        // Buscar el administrador
        $admin = \App\Models\User::where('role', 'admin')->first();
        
        if ($admin) {
            // Crear notificación para el administrador
            Notification::create([
                'user_id' => $admin->id,
                'from_user_id' => $currentUserId,
                'organic_id' => $organic->id,
                'type' => 'delete_request',
                'status' => 'pending',
                'message' => (auth()->check() ? auth()->user()->name : 'Usuario') . ' solicita permiso para eliminar el registro #' . str_pad($organic->id, 3, '0', STR_PAD_LEFT)
            ]);
        }

        return redirect()->route('aprendiz.organic.index')
            ->with('success', 'Solicitud de eliminación enviada al administrador. Recibirá una notificación cuando sea aprobada.');
    }
}
