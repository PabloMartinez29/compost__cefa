<?php

namespace App\Http\Controllers;

use App\Models\Aprendiz;
use App\Models\Notification;
use App\Models\Organic;
use App\Models\Composting;
use App\Models\Tracking;
use App\Models\Fertilizer;
use Illuminate\Http\Request;

class AprendizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = auth()->id();
        
        // Estadísticas de residuos orgánicos creados por el aprendiz
        $organicStats = [
            'total_records' => Organic::where('created_by', $userId)->count(),
            'total_weight' => Organic::where('created_by', $userId)->sum('weight'),
            'today_records' => Organic::where('created_by', $userId)->whereDate('created_at', today())->count(),
            'today_weight' => Organic::where('created_by', $userId)->whereDate('created_at', today())->sum('weight'),
        ];
        
        // Estadísticas de pilas de compostaje creadas por el aprendiz
        $compostingStats = [
            'total_piles' => Composting::where('created_by', $userId)->count(),
            'active_piles' => Composting::where('created_by', $userId)->whereNull('end_date')->count(),
            'completed_piles' => Composting::where('created_by', $userId)->whereNotNull('end_date')->count(),
        ];
        
        // Estadísticas de seguimientos registrados por el aprendiz
        // Contar seguimientos de las pilas creadas por el aprendiz
        $myCompostingIds = Composting::where('created_by', $userId)->pluck('id');
        $trackingStats = [
            'total_trackings' => Tracking::whereIn('composting_id', $myCompostingIds)->count(),
            'today_trackings' => Tracking::whereIn('composting_id', $myCompostingIds)->whereDate('created_at', today())->count(),
        ];
        
        // Estadísticas de abonos creados por el aprendiz
        $fertilizerStats = [
            'total_records' => Fertilizer::count(), // El aprendiz puede ver todos los abonos
            'total_amount' => Fertilizer::sum('amount'),
        ];
        
        // Notificaciones pendientes
        $pendingNotifications = Notification::where('from_user_id', $userId)
            ->where('type', 'delete_request')
            ->where('status', 'pending')
            ->count();
        
        return view('aprendiz.dashboard', compact('organicStats', 'compostingStats', 'trackingStats', 'fertilizerStats', 'pendingNotifications'));
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
    public function show(Aprendiz $aprendiz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aprendiz $aprendiz)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aprendiz $aprendiz)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aprendiz $aprendiz)
    {
        //
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead(Notification $notification)
    {
        // Verify that the notification belongs to the authenticated user
        if ($notification->from_user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $notification->update(['read_at' => now()]);
        
        return response()->json(['success' => true, 'message' => 'Notificación marcada como leída']);
    }

    /**
     * Show notifications history for apprentice
     */
    public function notificationsHistory()
    {
        $notifications = Notification::where('from_user_id', auth()->id())
            ->where('type', 'delete_request')
            ->with(['organic'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('aprendiz.notifications.history', compact('notifications'));
    }
}
