<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Machinery;
use App\Models\Notification;
use App\Models\Organic;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener estadísticas de maquinaria
        $totalMachinery = Machinery::count();
        $machineryStats = [
            'total' => $totalMachinery,
            'operational' => $totalMachinery > 0 ? ceil($totalMachinery * 0.8) : 0, // Aproximación del 80%
            'needs_maintenance' => $totalMachinery > 0 ? floor($totalMachinery * 0.2) : 0 // Aproximación del 20%
        ];

        // Obtener estadísticas de residuos orgánicos
        $organicStats = [
            'total_weight' => Organic::sum('weight'),
            'total_records' => Organic::count(),
            'today_records' => Organic::whereDate('created_at', today())->count(),
            'today_weight' => Organic::whereDate('created_at', today())->sum('weight'),
            'this_month_weight' => Organic::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)
                                        ->sum('weight'),
            'by_type' => Organic::selectRaw('type, COUNT(*) as count, SUM(weight) as total_weight')
                              ->groupBy('type')
                              ->get()
        ];

        // Obtener estadísticas de usuarios (aprendices)
        $userStats = [
            'total_apprentices' => User::where('role', 'aprendiz')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'active_today' => User::whereDate('updated_at', today())->count()
        ];

        // Obtener estadísticas de notificaciones
        $notificationStats = [
            'pending_requests' => Notification::where('user_id', auth()->check() ? auth()->id() : null)
                                            ->where('type', 'delete_request')
                                            ->where('status', 'pending')
                                            ->count(),
            'total_processed' => Notification::where('user_id', auth()->check() ? auth()->id() : null)
                                           ->where('type', 'delete_request')
                                           ->whereIn('status', ['approved', 'rejected'])
                                           ->count()
        ];

        return view('admin.dashboard', compact('machineryStats', 'organicStats', 'userStats', 'notificationStats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //
    }

    /**
     * Approve a delete request notification
     */
    public function approveNotification(Notification $notification)
    {
        $notification->update([
            'status' => 'approved',
            'read_at' => null // Reset read_at so apprentice sees the response
        ]);
        
        // Crear notificación de respuesta para el aprendiz
        if ($notification->composting_id) {
            // Es una notificación de compostaje
            $composting = \App\Models\Composting::find($notification->composting_id);
            if ($composting) {
                \App\Models\Notification::create([
                    'user_id' => $notification->from_user_id, // El aprendiz que hizo la solicitud
                    'from_user_id' => auth()->id(), // El administrador
                    'composting_id' => $notification->composting_id,
                    'type' => 'delete_request',
                    'status' => 'approved',
                    'message' => 'Su solicitud de eliminación ha sido APROBADA. Ahora puede eliminar la pila de compostaje #' . $composting->formatted_pile_num
                ]);
            }
        } else {
            // Es una notificación de residuos orgánicos (lógica existente)
            \App\Models\Notification::create([
                'user_id' => $notification->from_user_id, // El aprendiz que hizo la solicitud
                'from_user_id' => auth()->id(), // El administrador
                'organic_id' => $notification->organic_id,
                'type' => 'delete_request',
                'status' => 'approved',
                'message' => 'Su solicitud de eliminación ha sido APROBADA. Ahora puede eliminar el registro #' . str_pad($notification->organic_id, 3, '0', STR_PAD_LEFT)
            ]);
        }
        
        return response()->json(['success' => true, 'message' => 'Solicitud aprobada exitosamente']);
    }

    /**
     * Reject a delete request notification
     */
    public function rejectNotification(Notification $notification)
    {
        $notification->update([
            'status' => 'rejected',
            'read_at' => null // Reset read_at so apprentice sees the response
        ]);
        
        // Crear notificación de respuesta para el aprendiz
        if ($notification->composting_id) {
            // Es una notificación de compostaje
            $composting = \App\Models\Composting::find($notification->composting_id);
            if ($composting) {
                \App\Models\Notification::create([
                    'user_id' => $notification->from_user_id, // El aprendiz que hizo la solicitud
                    'from_user_id' => auth()->id(), // El administrador
                    'composting_id' => $notification->composting_id,
                    'type' => 'delete_request',
                    'status' => 'rejected',
                    'message' => 'Su solicitud de eliminación ha sido RECHAZADA. No puede eliminar la pila de compostaje #' . $composting->formatted_pile_num
                ]);
            }
        } else {
            // Es una notificación de residuos orgánicos
            \App\Models\Notification::create([
                'user_id' => $notification->from_user_id, // El aprendiz que hizo la solicitud
                'from_user_id' => auth()->id(), // El administrador
                'organic_id' => $notification->organic_id,
                'type' => 'delete_request',
                'status' => 'rejected',
                'message' => 'Su solicitud de eliminación ha sido RECHAZADA. No puede eliminar el registro #' . str_pad($notification->organic_id, 3, '0', STR_PAD_LEFT)
            ]);
        }
        
        return response()->json(['success' => true, 'message' => 'Solicitud rechazada']);
    }

    /**
     * Show notifications history for admin
     */
    public function notificationsHistory()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->where('type', 'delete_request')
            ->with(['fromUser', 'organic', 'composting'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.notifications.history', compact('notifications'));
    }
}
