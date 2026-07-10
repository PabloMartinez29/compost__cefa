<?php

// Controlador Admin NotificationController — Gestión de notificaciones
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Organic;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Obtener notificaciones pendientes
    public function getNotifications()
    {
        $notifications = Notification::with(['fromUser', 'organic', 'fertilizer', 'composting', 'machinery', 'maintenance', 'supplier', 'usageControl'])
            ->where('user_id', auth()->id())
            ->pending()
            ->unread()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    // Aprobar solicitud de eliminación
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
        $notificationData = [
            'user_id' => $notification->from_user_id, // El aprendiz que hizo la solicitud
            'from_user_id' => auth()->id(), // El administrador
            'type' => 'delete_request',
            'status' => 'approved',
        ];

        // Agregar el ID correspondiente según el tipo de registro
        if ($notification->organic_id) {
            $notificationData['organic_id'] = $notification->organic_id;
            $notificationData['message'] = 'Su solicitud de eliminación ha sido APROBADA. Ahora puede eliminar el registro #' . str_pad($notification->organic_id, 3, '0', STR_PAD_LEFT);
        } elseif ($notification->fertilizer_id) {
            $notificationData['fertilizer_id'] = $notification->fertilizer_id;
            $notificationData['message'] = 'Su solicitud de eliminación ha sido APROBADA. Ahora puede eliminar el registro de abono #' . str_pad($notification->fertilizer_id, 3, '0', STR_PAD_LEFT);
        } elseif ($notification->composting_id) {
            $notificationData['composting_id'] = $notification->composting_id;
            $notificationData['message'] = 'Su solicitud de eliminación ha sido APROBADA. Ahora puede eliminar el registro #' . str_pad($notification->composting_id, 3, '0', STR_PAD_LEFT);
        } elseif ($notification->tracking_id) {
            $notificationData['tracking_id'] = $notification->tracking_id;
            $notificationData['message'] = 'Su solicitud de eliminación ha sido APROBADA. Ahora puede eliminar el seguimiento #' . str_pad($notification->tracking_id, 3, '0', STR_PAD_LEFT);
        } elseif ($notification->machinery_id) {
            $notificationData['machinery_id'] = $notification->machinery_id;
            $notificationData['message'] = 'Su solicitud de eliminación ha sido APROBADA. Ahora puede eliminar el registro #' . str_pad($notification->machinery_id, 3, '0', STR_PAD_LEFT);
        } elseif ($notification->maintenance_id) {
            $notificationData['maintenance_id'] = $notification->maintenance_id;
            $notificationData['message'] = 'Su solicitud de eliminación ha sido APROBADA. Ahora puede eliminar el registro #' . str_pad($notification->maintenance_id, 3, '0', STR_PAD_LEFT);
        } elseif ($notification->supplier_id) {
            $notificationData['supplier_id'] = $notification->supplier_id;
            $notificationData['message'] = 'Su solicitud de eliminación ha sido APROBADA. Ahora puede eliminar el registro #' . str_pad($notification->supplier_id, 3, '0', STR_PAD_LEFT);
        } elseif ($notification->usage_control_id) {
            $notificationData['usage_control_id'] = $notification->usage_control_id;
            $notificationData['message'] = 'Su solicitud de eliminación ha sido APROBADA. Ahora puede eliminar el registro #' . str_pad($notification->usage_control_id, 3, '0', STR_PAD_LEFT);
        }

        Notification::create($notificationData);

        return response()->json([
            'success' => true,
            'message' => 'Solicitud aprobada. El aprendiz ahora puede eliminar el registro.'
        ]);
    }

    // Rechazar solicitud de eliminación
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
        $notificationData = [
            'user_id' => $notification->from_user_id, // El aprendiz que hizo la solicitud
            'from_user_id' => auth()->id(), // El administrador
            'type' => 'delete_request',
            'status' => 'rejected',
        ];

        // Agregar el ID correspondiente según el tipo de registro
        if ($notification->organic_id) {
            $notificationData['organic_id'] = $notification->organic_id;
            $notificationData['message'] = 'Su solicitud de eliminación ha sido RECHAZADA. No puede eliminar el registro #' . str_pad($notification->organic_id, 3, '0', STR_PAD_LEFT);
        } elseif ($notification->fertilizer_id) {
            $notificationData['fertilizer_id'] = $notification->fertilizer_id;
            $notificationData['message'] = 'Su solicitud de eliminación ha sido RECHAZADA. No puede eliminar el registro de abono #' . str_pad($notification->fertilizer_id, 3, '0', STR_PAD_LEFT);
        } elseif ($notification->composting_id) {
            $notificationData['composting_id'] = $notification->composting_id;
            $notificationData['message'] = 'Su solicitud de eliminación ha sido RECHAZADA. No puede eliminar el registro #' . str_pad($notification->composting_id, 3, '0', STR_PAD_LEFT);
        } elseif ($notification->tracking_id) {
            $notificationData['tracking_id'] = $notification->tracking_id;
            $notificationData['message'] = 'Su solicitud de eliminación ha sido RECHAZADA. No puede eliminar el seguimiento #' . str_pad($notification->tracking_id, 3, '0', STR_PAD_LEFT);
        } elseif ($notification->machinery_id) {
            $notificationData['machinery_id'] = $notification->machinery_id;
            $notificationData['message'] = 'Su solicitud de eliminación ha sido RECHAZADA. No puede eliminar el registro #' . str_pad($notification->machinery_id, 3, '0', STR_PAD_LEFT);
        } elseif ($notification->maintenance_id) {
            $notificationData['maintenance_id'] = $notification->maintenance_id;
            $notificationData['message'] = 'Su solicitud de eliminación ha sido RECHAZADA. No puede eliminar el registro #' . str_pad($notification->maintenance_id, 3, '0', STR_PAD_LEFT);
        } elseif ($notification->supplier_id) {
            $notificationData['supplier_id'] = $notification->supplier_id;
            $notificationData['message'] = 'Su solicitud de eliminación ha sido RECHAZADA. No puede eliminar el registro #' . str_pad($notification->supplier_id, 3, '0', STR_PAD_LEFT);
        } elseif ($notification->usage_control_id) {
            $notificationData['usage_control_id'] = $notification->usage_control_id;
            $notificationData['message'] = 'Su solicitud de eliminación ha sido RECHAZADA. No puede eliminar el registro #' . str_pad($notification->usage_control_id, 3, '0', STR_PAD_LEFT);
        }

        Notification::create($notificationData);

        return response()->json([
            'success' => true,
            'message' => 'Solicitud rechazada.'
        ]);
    }

    // Marcar notificación como leída
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        try {
            $notification->update(['read_at' => now()]);

            if ($notification->type === 'maintenance_reminder' && $notification->machinery_id) {
                // Marcar como leída para todos los demás usuarios (aprendices, etc.)
                \App\Models\Notification::where('machinery_id', $notification->machinery_id)
                    ->where('type', 'maintenance_reminder')
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);

                $machinery = \App\Models\Machinery::find($notification->machinery_id);
                if ($machinery) {
                    try {
                        $machinery->scheduleNextMaintenanceDue();
                    } catch (\Throwable $e) {
                        \Log::warning('Al marcar recordatorio como leído no se pudo reiniciar cronómetro de maquinaria.', [
                            'machinery_id' => $notification->machinery_id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            \Log::error('Error al marcar notificación como leída', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'No se pudo marcar como leída.', 'message' => $e->getMessage()], 500);
        }
    }
}
