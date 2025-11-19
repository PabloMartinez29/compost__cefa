<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Organic;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Obtener notificaciones pendientes
     */
    public function getNotifications()
    {
        $notifications = Notification::with(['fromUser', 'organic'])
            ->where('user_id', auth()->id())
            ->pending()
            ->unread()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    /**
     * Aprobar solicitud de eliminación
     */
    public function approveDelete(Request $request)
    {
        $notification = Notification::findOrFail($request->notification_id);
        
        // Verificar que la notificación pertenece al administrador actual
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Actualizar estado de la notificación
        $notification->update([
            'status' => 'approved',
            'read_at' => now()
        ]);

        // Crear notificación de respuesta para el aprendiz
        Notification::create([
            'user_id' => $notification->from_user_id, // El aprendiz que hizo la solicitud
            'from_user_id' => auth()->id(), // El administrador
            'organic_id' => $notification->organic_id,
            'type' => 'delete_request',
            'status' => 'approved',
            'message' => 'Su solicitud de eliminación ha sido APROBADA. Ahora puede eliminar el registro #' . str_pad($notification->organic_id, 3, '0', STR_PAD_LEFT)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Solicitud aprobada. El aprendiz ahora puede eliminar el registro.'
        ]);
    }

    /**
     * Rechazar solicitud de eliminación
     */
    public function rejectDelete(Request $request)
    {
        $notification = Notification::findOrFail($request->notification_id);
        
        // Verificar que la notificación pertenece al administrador actual
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Actualizar estado de la notificación
        $notification->update([
            'status' => 'rejected',
            'read_at' => now()
        ]);

        // Crear notificación de respuesta para el aprendiz
        Notification::create([
            'user_id' => $notification->from_user_id, // El aprendiz que hizo la solicitud
            'from_user_id' => auth()->id(), // El administrador
            'organic_id' => $notification->organic_id,
            'type' => 'delete_request',
            'status' => 'rejected',
            'message' => 'Su solicitud de eliminación ha sido RECHAZADA. No puede eliminar el registro #' . str_pad($notification->organic_id, 3, '0', STR_PAD_LEFT)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Solicitud rechazada.'
        ]);
    }

    /**
     * Marcar notificación como leída
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $notification->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
