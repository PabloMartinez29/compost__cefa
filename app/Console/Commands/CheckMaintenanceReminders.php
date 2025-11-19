<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Machinery;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class CheckMaintenanceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'machinery:check-maintenance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar maquinarias que requieren mantenimiento y crear notificaciones para administradores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando maquinarias que requieren mantenimiento...');
        
        // Obtener todas las maquinarias
        $machineries = Machinery::with('maintenances')->get();
        
        // Obtener todos los administradores
        $admins = User::where('role', 'admin')->get();
        
        if ($admins->isEmpty()) {
            $this->warn('No se encontraron administradores para enviar notificaciones.');
            return Command::SUCCESS;
        }
        
        $notificationsCreated = 0;
        
        foreach ($machineries as $machinery) {
            // Calcular el estado manualmente para asegurar que sea correcto
            $requiresMaintenance = $this->checkIfMachineryRequiresMaintenance($machinery);
            
            // Debug: mostrar información de cada maquinaria
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
                $daysSince = abs(now()->diffInDays($refDate));
            } else {
                $daysSince = abs(now()->diffInDays($machinery->start_func));
            }
            
            $freqDays = $this->getMaintenanceFrequencyInDays($machinery->maint_freq);
            $this->line("  {$machinery->name}: {$daysSince} días desde último mantenimiento, frecuencia: {$machinery->maint_freq} ({$freqDays} días), requiere: " . ($requiresMaintenance ? 'SÍ' : 'NO'));
            
            if ($requiresMaintenance) {
                $this->info("Maquinaria '{$machinery->name}' requiere mantenimiento.");
                
                // Calcular días desde el último mantenimiento
                $lastMaintenance = $machinery->maintenances()
                    ->where('type', 'M')
                    ->whereNotNull('end_date')
                    ->latest('end_date')
                    ->first();
                
                // Si no hay mantenimiento con end_date, buscar el último mantenimiento tipo M
                if (!$lastMaintenance) {
                    $lastMaintenance = $machinery->maintenances()
                        ->where('type', 'M')
                        ->latest('date')
                        ->first();
                }
                
                $daysSinceMaintenance = 0;
                $referenceDate = null;
                
                if ($lastMaintenance) {
                    $referenceDate = $lastMaintenance->end_date ?? $lastMaintenance->date;
                    $daysSinceMaintenance = abs(now()->diffInDays($referenceDate));
                } else {
                    // Si no hay mantenimientos previos, usar la fecha de inicio de funcionamiento
                    $referenceDate = $machinery->start_func;
                    $daysSinceMaintenance = abs(now()->diffInDays($referenceDate));
                }
                
                // Obtener frecuencia de mantenimiento en días
                $maintenanceFreqDays = $this->getMaintenanceFrequencyInDays($machinery->maint_freq);
                
                // Formatear días para mostrar (redondear a entero)
                $daysSinceFormatted = (int)round($daysSinceMaintenance);
                
                foreach ($admins as $admin) {
                    // Verificar si ya existe una notificación pendiente para esta maquinaria Y este admin
                    $existingNotification = Notification::where('user_id', $admin->id)
                        ->where('machinery_id', $machinery->id)
                        ->where('type', 'maintenance_reminder')
                        ->where('status', 'pending')
                        ->whereNull('read_at')
                        ->first();
                    
                    // Si no existe una notificación pendiente, crear una nueva
                    if (!$existingNotification) {
                        // Crear notificación
                        Notification::create([
                            'user_id' => $admin->id,
                            'from_user_id' => $admin->id, // El sistema es quien envía la notificación
                            'machinery_id' => $machinery->id,
                            'type' => 'maintenance_reminder',
                            'status' => 'pending',
                            'message' => "La maquinaria '{$machinery->name}' requiere mantenimiento. Han pasado {$daysSinceFormatted} día" . ($daysSinceFormatted != 1 ? 's' : '') . " desde el último mantenimiento. Frecuencia requerida: {$machinery->maint_freq}.",
                        ]);
                        
                        $notificationsCreated++;
                        $this->info("  ✓ Notificación creada para admin: {$admin->name}");
                    } else {
                        $this->info("  - Ya existe notificación pendiente para admin: {$admin->name}");
                    }
                }
            }
        }
        
        if ($notificationsCreated === 0) {
            $this->info('No se encontraron maquinarias que requieran mantenimiento o ya existen notificaciones pendientes para todos los administradores.');
        } else {
            $this->info("Se crearon {$notificationsCreated} notificación(es) de mantenimiento.");
        }
        
        return Command::SUCCESS;
    }
    
    /**
     * Verificar si una maquinaria requiere mantenimiento
     */
    private function checkIfMachineryRequiresMaintenance(Machinery $machinery): bool
    {
        // Verificar si hay un mantenimiento activo (tipo 'M' sin fecha de fin)
        $activeMaintenance = $machinery->maintenances()
            ->where('type', 'M')
            ->whereNull('end_date')
            ->latest('date')
            ->first();
        
        if ($activeMaintenance) {
            return false; // Ya está en mantenimiento
        }
        
        // Buscar el último mantenimiento completado (tipo 'M' con fecha de fin)
        $lastMaintenance = $machinery->maintenances()
            ->where('type', 'M')
            ->whereNotNull('end_date')
            ->latest('end_date')
            ->first();
        
        // Si no hay mantenimientos completados, buscar cualquier mantenimiento tipo 'M'
        if (!$lastMaintenance) {
            $lastMaintenance = $machinery->maintenances()
                ->where('type', 'M')
                ->latest('date')
                ->first();
        }
        
        // Si no hay mantenimientos tipo 'M', verificar desde la fecha de inicio de funcionamiento
        if (!$lastMaintenance) {
            // Si no hay mantenimientos, verificar desde start_func
            $referenceDate = $machinery->start_func;
            // Solo verificar si start_func no es futuro
            if ($referenceDate > now()) {
                return false; // La máquina aún no ha comenzado a funcionar
            }
            $daysSinceStart = abs(now()->diffInDays($referenceDate));
            $maintenanceFreqDays = $this->getMaintenanceFrequencyInDays($machinery->maint_freq);
            
            return $daysSinceStart >= $maintenanceFreqDays;
        }
        
        // Calcular días desde el último mantenimiento
        $referenceDate = $lastMaintenance->end_date ?? $lastMaintenance->date;
        // Si la fecha de referencia es futura, no requiere mantenimiento aún
        if ($referenceDate > now()) {
            return false;
        }
        $daysSinceLastMaintenance = abs(now()->diffInDays($referenceDate));
        $maintenanceFreqDays = $this->getMaintenanceFrequencyInDays($machinery->maint_freq);
        
        return $daysSinceLastMaintenance >= $maintenanceFreqDays;
    }
    
    /**
     * Convertir frecuencia de mantenimiento a días
     */
    private function getMaintenanceFrequencyInDays(?string $freq): int
    {
        if (!$freq) {
            return 30; // Default: mensual
        }
        
        $freq = strtolower(trim($freq));
        
        if (str_contains($freq, 'diario') || str_contains($freq, 'día') || str_contains($freq, 'daily')) {
            return 1;
        } elseif (str_contains($freq, 'semanal') || str_contains($freq, 'semana') || str_contains($freq, 'weekly')) {
            return 7;
        } elseif (str_contains($freq, 'mensual') || str_contains($freq, 'mes') || str_contains($freq, 'monthly')) {
            return 30;
        } elseif (str_contains($freq, 'trimestral') || str_contains($freq, 'trimestre') || str_contains($freq, 'quarterly')) {
            return 90;
        } elseif (str_contains($freq, 'semestral') || str_contains($freq, 'semestre') || str_contains($freq, 'semiannual')) {
            return 180;
        } elseif (str_contains($freq, 'anual') || str_contains($freq, 'año') || str_contains($freq, 'annual') || str_contains($freq, 'yearly')) {
            return 365;
        } elseif (str_contains($freq, 'bimestral') || str_contains($freq, 'bimestre')) {
            return 60;
        }
        
        // Intentar extraer número de días si está en formato numérico
        if (preg_match('/(\d+)\s*d[íi]a/i', $freq, $matches)) {
            return (int)$matches[1];
        }
        
        return 30; // Default: mensual
    }
}
