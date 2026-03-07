<?php

// Controlador AprendizController — Dashboard y estadísticas del aprendiz
namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Organic;
use App\Models\Composting;
use App\Models\Tracking;
use App\Models\Fertilizer;
use Illuminate\Http\Request;

// Controlador principal del Aprendiz
class AprendizController extends Controller
{
    // Dashboard aprendiz: muestra métricas filtradas por created_by
    public function index()
    {
        $userId = auth()->id();

        $organicStats = [
            'total_records' => Organic::where('created_by', $userId)->count(),
            'total_weight' => Organic::where('created_by', $userId)->sum('weight'),
            'today_records' => Organic::where('created_by', $userId)->whereDate('created_at', today())->count(),
            'today_weight' => Organic::where('created_by', $userId)->whereDate('created_at', today())->sum('weight'),
        ];

        $compostingStats = [
            'total_piles' => Composting::where('created_by', $userId)->count(),
            'active_piles' => Composting::where('created_by', $userId)->whereNull('end_date')->count(),
            'completed_piles' => Composting::where('created_by', $userId)->whereNotNull('end_date')->count(),
        ];

        $myCompostingIds = Composting::where('created_by', $userId)->pluck('id');
        $trackingStats = [
            'total_trackings' => Tracking::whereIn('composting_id', $myCompostingIds)->count(),
            'today_trackings' => Tracking::whereIn('composting_id', $myCompostingIds)->whereDate('created_at', today())->count(),
        ];

        $fertilizerStats = [
            'total_records' => Fertilizer::count(),
            'total_amount' => Fertilizer::sum('amount'),
        ];

        $pendingNotifications = Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'pending')
            ->count();

        // Mostrar vista
        return view('aprendiz.dashboard', compact('organicStats', 'compostingStats', 'trackingStats', 'fertilizerStats', 'pendingNotifications'));
    }

    // Marcar notificación como leída
    public function markNotificationAsRead(Notification $notification)
    {
        $isRecipient = $notification->user_id === auth()->id();
        $isSenderAndProcessed = $notification->from_user_id === auth()->id()
            && in_array($notification->status, ['approved', 'rejected'], true);

        if (! $isRecipient && ! $isSenderAndProcessed) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $notification->update(['read_at' => now()]);

        if ($notification->type === 'maintenance_reminder' && $notification->machinery_id) {
            $machinery = \App\Models\Machinery::find($notification->machinery_id);
            if ($machinery) {
                $machinery->scheduleNextMaintenanceDue();
            }
        }

        return response()->json(['success' => true, 'message' => 'Notificación marcada como leída']);
    }

    // Historial de solicitudes de eliminación enviadas por
    public function notificationsHistory()
    {
        $notifications = Notification::where('from_user_id', auth()->id())
            ->where('type', 'delete_request')
            ->with(['organic'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Mostrar vista
        return view('aprendiz.notifications.history', compact('notifications'));
    }
}
