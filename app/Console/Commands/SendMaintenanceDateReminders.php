<?php

// Comando SendMaintenanceDateReminders — Envía alertas de mantenimiento
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Maintenance;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class SendMaintenanceDateReminders extends Command
{
    protected $signature = 'machinery:send-maintenance-date-reminders';

    protected $description = 'El día de la fecha del mantenimiento envía alerta a todos (admin y aprendiz) con el rol del creador';

    public function handle()
    {
        $today = Carbon::today()->toDateString();
        $this->info("Buscando mantenimientos programados para hoy ({$today})...");

        $maintenances = Maintenance::with(['machinery', 'creator'])
            ->whereDate('date', $today)
            ->get();

        if ($maintenances->isEmpty()) {
            $this->info('No hay mantenimientos programados para hoy.');
            return Command::SUCCESS;
        }

        $allUsers = User::all();
        if ($allUsers->isEmpty()) {
            $this->warn('No hay usuarios en el sistema.');
            return Command::SUCCESS;
        }

        $notificationsCreated = 0;

        foreach ($maintenances as $maintenance) {
            $machineryName = $maintenance->machinery->name ?? 'Maquinaria';
            $tipoTexto = $maintenance->type === 'M' ? 'Mantenimiento' : 'Operación';
            $rolCreador = 'Desconocido';
            if ($maintenance->creator) {
                $rolCreador = $maintenance->creator->role === 'admin' ? 'Administrador' : 'Aprendiz';
            }
            $message = "{$tipoTexto} programado para hoy: {$machineryName}. Creado por: {$rolCreador}.";

            foreach ($allUsers as $user) {
                $yaEnviada = Notification::where('maintenance_id', $maintenance->id)
                    ->where('user_id', $user->id)
                    ->where('type', 'maintenance_reminder')
                    ->whereDate('created_at', $today)
                    ->exists();

                if (!$yaEnviada) {
                    Notification::create([
                        'user_id' => $user->id,
                        'from_user_id' => $maintenance->created_by ?? $user->id,
                        'machinery_id' => $maintenance->machinery_id,
                        'maintenance_id' => $maintenance->id,
                        'type' => 'maintenance_reminder',
                        'status' => 'pending',
                        'message' => $message,
                    ]);
                    $notificationsCreated++;
                    $this->line("  ✓ Alerta enviada a: {$user->name} ({$user->role}) - {$machineryName}");
                }
            }
        }

        $this->info("Se crearon {$notificationsCreated} notificación(es) de mantenimiento para hoy.");
        return Command::SUCCESS;
    }
}
