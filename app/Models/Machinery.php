<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Machinery extends Model
{
    use HasFactory;

    protected $table = 'machineries';

    protected $fillable = [
        'name',
        'location',
        'brand',
        'model',
        'serial',
        'start_func',
        'maint_freq',
        'image',
    ];

    protected $casts = [
        'start_func' => 'date',
    ];

    // Relación con mantenimientos
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    // Relación con proveedores
    public function supplier()
    {
        return $this->hasOne(Supplier::class);
    }

    // Relación con controles de uso
    public function usageControls()
    {
        return $this->hasMany(UsageControl::class);
    }

    // Accessor para obtener el estado de la maquinaria basado en mantenimiento
    public function getStatusAttribute()
    {
        // Verificar si hay registros de mantenimiento para esta maquinaria
        $hasMaintenances = $this->maintenances()->exists();
        
        // Si no hay registros de mantenimiento, retornar "Sin actividad"
        if (!$hasMaintenances) {
            return 'Sin actividad';
        }
        
        // Verificar si hay un mantenimiento activo (tipo 'M' sin fecha de fin)
        $activeMaintenance = $this->maintenances()
            ->where('type', 'M')
            ->whereNull('end_date')
            ->latest('date')
            ->first();
        
        if ($activeMaintenance) {
            return 'En mantenimiento';
        }
        
        // Buscar el último mantenimiento completado (tipo 'M' con fecha de fin)
        $lastMaintenance = $this->maintenances()
            ->where('type', 'M')
            ->whereNotNull('end_date')
            ->latest('end_date')
            ->first();
        
        // Si no hay mantenimientos completados, buscar cualquier mantenimiento tipo 'M'
        if (!$lastMaintenance) {
            $lastMaintenance = $this->maintenances()
                ->where('type', 'M')
                ->latest('date')
                ->first();
        }
        
        // Si no hay mantenimientos tipo 'M' pero hay registros (solo tipo 'O'), retornar "Operación"
        if (!$lastMaintenance) {
            // Verificar si hay registros de tipo 'O' (Operación)
            $hasOperations = $this->maintenances()->where('type', 'O')->exists();
            if ($hasOperations) {
                return 'Operación';
            }
            return 'Sin actividad';
        }

        // Si el último mantenimiento tiene fecha de fin, usar esa fecha para calcular
        $referenceDate = $lastMaintenance->end_date ?? $lastMaintenance->date;
        $daysSinceLastMaintenance = now()->diffInDays($referenceDate);
        $maintenanceFreqDays = $this->getMaintenanceFrequencyInDays();

        if ($daysSinceLastMaintenance >= $maintenanceFreqDays) {
            return 'Mantenimiento requerido';
        }

        return 'Operación';
    }

    // Helper para convertir frecuencia de mantenimiento a días
    private function getMaintenanceFrequencyInDays()
    {
        $freq = strtolower($this->maint_freq);
        
        if (str_contains($freq, 'diario') || str_contains($freq, 'día')) {
            return 1;
        } elseif (str_contains($freq, 'semanal') || str_contains($freq, 'semana')) {
            return 7;
        } elseif (str_contains($freq, 'mensual') || str_contains($freq, 'mes')) {
            return 30;
        } elseif (str_contains($freq, 'trimestral')) {
            return 90;
        } elseif (str_contains($freq, 'semestral')) {
            return 180;
        } elseif (str_contains($freq, 'anual') || str_contains($freq, 'año')) {
            return 365;
        }

        return 30; // Default: mensual
    }
}
