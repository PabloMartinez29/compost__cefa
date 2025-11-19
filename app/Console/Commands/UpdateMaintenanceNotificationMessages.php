<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\Machinery;

class UpdateMaintenanceNotificationMessages extends Command
{
    protected $signature = 'notifications:update-maintenance-messages';
    protected $description = 'Actualizar mensajes de notificaciones de mantenimiento para mostrar días enteros';

    public function handle()
    {
        $this->info('Actualizando mensajes de notificaciones de mantenimiento...');
        
        $notifications = Notification::where('type', 'maintenance_reminder')
            ->whereNull('read_at')
            ->with('machinery')
            ->get();
        
        $updated = 0;
        
        foreach ($notifications as $notification) {
            if (!$notification->machinery) {
                continue;
            }
            
            $machinery = $notification->machinery;
            
            // Calcular días desde el último mantenimiento
            $lastMaintenance = $machinery->maintenances()
                ->where('type', 'M')
                ->whereNotNull('end_date')
                ->latest('end_date')
                ->first();
            
            if (!$lastMaintenance) {
                $lastMaintenance = $machinery->maintenances()
                    ->where('type', 'M')
                    ->latest('date')
                    ->first();
            }
            
            $daysSince = 0;
            if ($lastMaintenance) {
                $refDate = $lastMaintenance->end_date ?? $lastMaintenance->date;
                $daysSince = (int)round(abs(now()->diffInDays($refDate)));
            } else {
                $daysSince = (int)round(abs(now()->diffInDays($machinery->start_func)));
            }
            
            // Actualizar mensaje
            $notification->message = "La maquinaria '{$machinery->name}' requiere mantenimiento. Han pasado {$daysSince} día" . ($daysSince != 1 ? 's' : '') . " desde el último mantenimiento. Frecuencia requerida: {$machinery->maint_freq}.";
            $notification->save();
            
            $updated++;
        }
        
        $this->info("Se actualizaron {$updated} notificación(es).");
        
        return Command::SUCCESS;
    }
}

