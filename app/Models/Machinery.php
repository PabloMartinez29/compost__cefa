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
        $lastMaintenance = $this->maintenances()->where('type', 'M')->latest('date')->first();
        
        if (!$lastMaintenance) {
            // Si no hay mantenimientos registrados, verificar tiempo desde inicio
            $daysSinceStart = now()->diffInDays($this->start_func);
            $maintenanceFreqDays = $this->getMaintenanceFrequencyInDays();
            
            if ($daysSinceStart >= $maintenanceFreqDays) {
                return 'Mantenimiento requerido';
            }
            return 'Sin mantenimiento registrado';
        }

        $daysSinceLastMaintenance = now()->diffInDays($lastMaintenance->date);
        $maintenanceFreqDays = $this->getMaintenanceFrequencyInDays();

        if ($daysSinceLastMaintenance >= $maintenanceFreqDays) {
            return 'Mantenimiento requerido';
        }

        return 'Operativa';
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
