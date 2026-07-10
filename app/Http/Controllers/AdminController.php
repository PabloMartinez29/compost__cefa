<?php

// Controlador AdminController — Dashboard y estadísticas del administrador
namespace App\Http\Controllers;

use App\Http\Traits\NotificationHandler;
use App\Models\Machinery;
use App\Models\Notification;
use App\Models\Organic;
use App\Models\Composting;
use App\Models\Fertilizer;
use App\Models\User;
use Illuminate\Http\Request;

// Controlador principal del Administrador
class AdminController extends Controller
{
    use NotificationHandler;

    // Dashboard admin: recopila estadísticas de todos los
    public function index()
    {
        $totalMachinery = Machinery::count();
        $machineryStats = [
            'total' => $totalMachinery,
            'operational' => $totalMachinery > 0 ? ceil($totalMachinery * 0.8) : 0,
            'needs_maintenance' => $totalMachinery > 0 ? floor($totalMachinery * 0.2) : 0
        ];

        $compostingStats = [
            'total' => Composting::count(),
            'active' => Composting::get()->filter(function ($c) { return $c->status !== 'Completada'; })->count(),
            'completed' => Composting::get()->filter(function ($c) { return $c->status === 'Completada'; })->count(),
        ];

        $fertilizerStats = [
            'total_amount' => Fertilizer::sum('amount'),
            'total_records' => Fertilizer::count(),
            'solid_amount' => Fertilizer::where('type', 'Solid')->sum('amount'),
            'liquid_amount' => Fertilizer::where('type', 'Liquid')->sum('amount'),
        ];

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

        $userStats = [
            'total_apprentices' => User::where('role', 'aprendiz')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'active_today' => User::whereDate('updated_at', today())->count()
        ];

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

        // Mostrar vista
        return view('admin.dashboard', compact(
            'machineryStats',
            'organicStats',
            'userStats',
            'notificationStats',
            'compostingStats',
            'fertilizerStats'
        ));
    }

    // Aprobar solicitud de eliminación de un aprendiz
    public function approveNotification(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $this->processNotificationResponse($notification, 'approved');

        return response()->json(['success' => true, 'message' => 'Solicitud aprobada exitosamente']);
    }

    // Rechazar solicitud de eliminación de un aprendiz
    public function rejectNotification(Notification $notification)
    {
        $this->processNotificationResponse($notification, 'rejected');

        return response()->json(['success' => true, 'message' => 'Solicitud rechazada']);
    }

    // Historial de notificaciones del admin
    public function notificationsHistory()
    {
        $userId = auth()->id();
        $types = ['delete_request', 'maintenance_reminder'];

        $notifications = Notification::where('user_id', $userId)
            ->whereIn('type', $types)
            ->with(['fromUser', 'organic', 'composting', 'machinery', 'maintenance', 'supplier', 'usageControl'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalCount = Notification::where('user_id', $userId)->whereIn('type', $types)->count();
        $pendingCount = Notification::where('user_id', $userId)->whereIn('type', $types)->where('status', 'pending')->count();
        $approvedCount = Notification::where('user_id', $userId)->whereIn('type', $types)->where('status', 'approved')->count();
        $rejectedCount = Notification::where('user_id', $userId)->whereIn('type', $types)->where('status', 'rejected')->count();

        // Mostrar vista
        return view('admin.notifications.history', compact(
            'notifications',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }
}
