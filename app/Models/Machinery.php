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
        'next_maintenance_due_at',
        'image',
        'created_by',
    ];

    protected $casts = [
        'start_func' => 'date',
        'next_maintenance_due_at' => 'datetime',
    ];

    /**
     * Atributos que se incluyen automáticamente al serializar a JSON
     */
    protected $appends = ['status'];

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

    /**
     * Estado en Identificación y especificaciones. Sincronizado con Control de actividades:
     * se usa el último registro de control de actividades (por updated_at y fecha);
     * status 'mantenimiento' → "Mantenimiento requerido", 'operativa' → "Operativa".
     */
    public function getStatusAttribute()
    {
        // Buscar en usageControls primero (Control de actividades)
        $lastUsageControl = $this->usageControls()
            ->orderByDesc('updated_at')
            ->orderByDesc('start_date')
            ->first();

        if ($lastUsageControl && $lastUsageControl->status) {
            return $lastUsageControl->status === 'mantenimiento' 
                ? 'Mantenimiento requerido' 
                : 'Operativa';
        }

        // Fallback a maintenances si no hay usageControls
        // Buscar el último registro de mantenimiento
        $lastActivity = $this->maintenances()
            ->orderByDesc('updated_at')
            ->orderByDesc('date')
            ->first();

        if (!$lastActivity) {
            return 'Sin mantenimiento registrado';
        }

        // Si el último registro es tipo 'M' (Mantenimiento) y NO tiene fecha de fin, está en mantenimiento
        if ($lastActivity->type === 'M' && is_null($lastActivity->end_date)) {
            return 'Mantenimiento requerido';
        }

        // Si es tipo 'O' (Operación) o tiene fecha de fin, está operativa
        return 'Operativa';
    }

    /**
     * Convierte frecuencia de mantenimiento a días (recordatorios y cronómetro).
     * Equivalencias: Diario = 1, Semanal = 7, Quincenal = 15, Mensual = 30,
     * Bimestral = 60, Trimestral = 90, Semestral = 180, Anual = 365.
     */
    public function getMaintenanceFrequencyInDays(): int
    {
        $freq = strtolower(trim($this->maint_freq ?? ''));
        if ($freq === '') {
            return 30;
        }
        if (str_contains($freq, 'diario') || str_contains($freq, 'día')) {
            return 1;   // Diario
        }
        if (str_contains($freq, 'semanal') || str_contains($freq, 'semana')) {
            return 7;   // Semanal
        }
        if (str_contains($freq, 'quincenal')) {
            return 15;  // Quincenal
        }
        if (str_contains($freq, 'mensual') || str_contains($freq, 'mes')) {
            return 30;  // Mensual
        }
        if (str_contains($freq, 'bimestral') || str_contains($freq, 'bimestre')) {
            return 60;  // Bimestral
        }
        if (str_contains($freq, 'trimestral') || str_contains($freq, 'trimestre')) {
            return 90;  // Trimestral
        }
        if (str_contains($freq, 'semestral') || str_contains($freq, 'semestre')) {
            return 180; // Semestral
        }
        if (str_contains($freq, 'anual') || str_contains($freq, 'año')) {
            return 365; // Anual
        }
        return 30;
    }

    /**
     * Fecha/hora en que vence el próximo mantenimiento (cronómetro).
     * Si la maquinaria está "En mantenimiento" (último registro tipo M), retorna null (cronómetro pausado).
     * Si está en Operación, usa next_maintenance_due_at o calcula por frecuencia.
     */
    public function getNextMaintenanceDueDateTime(): ?\Carbon\Carbon
    {
        $lastActivity = $this->maintenances()->orderByDesc('updated_at')->orderByDesc('date')->first();
        if ($lastActivity && $lastActivity->type === 'M') {
            return null; // En mantenimiento: cronómetro pausado
        }
        if ($this->next_maintenance_due_at) {
            return $this->next_maintenance_due_at;
        }
        $freqDays = $this->getMaintenanceFrequencyInDays();
        $lastMaintenance = $this->maintenances()
            ->where('type', 'M')
            ->whereNotNull('end_date')
            ->latest('end_date')
            ->first();
        if (!$lastMaintenance) {
            $lastMaintenance = $this->maintenances()
                ->where('type', 'M')
                ->latest('date')
                ->first();
        }
        $reference = $lastMaintenance
            ? ($lastMaintenance->end_date ?? $lastMaintenance->date)->copy()->startOfDay()
            : $this->start_func?->copy()->startOfDay();
        if (!$reference) {
            return null;
        }
        return $reference->copy()->addDays($freqDays);
    }

    /**
     * Programa el próximo vencimiento (al guardar maquinaria o al marcar alerta como leída).
     */
    public function scheduleNextMaintenanceDue(): void
    {
        $freqDays = $this->getMaintenanceFrequencyInDays();
        $this->next_maintenance_due_at = now()->addDays($freqDays);
        $this->saveQuietly();
    }

    /**
     * Indica si la maquinaria requiere mantenimiento por frecuencia (cronómetro en 0).
     */
    public function requiresMaintenanceByFrequency(): bool
    {
        $nextDue = $this->getNextMaintenanceDueDateTime();
        return $nextDue && now()->gte($nextDue);
    }

    /**
     * Alertas por frecuencia: crea notificaciones para maquinarias que requieren mantenimiento
     * según Identificación y especificaciones (maint_freq). No usa fechas de control de actividades.
     */
    public static function ensureFrequencyBasedRemindersForUser(\App\Models\User $user): void
    {
        $machineries = self::with('maintenances')->get();
        foreach ($machineries as $machinery) {
            if (!$machinery->requiresMaintenanceByFrequency()) {
                continue;
            }
            $exists = \App\Models\Notification::where('machinery_id', $machinery->id)
                ->where('user_id', $user->id)
                ->where('type', 'maintenance_reminder')
                ->whereNull('maintenance_id')
                ->whereNull('read_at')
                ->exists();
            if ($exists) {
                continue;
            }
            $message = 'La maquinaria ' . ($machinery->name ?? 'Maquinaria') . ' necesita mantenimiento. Frecuencia: ' . ($machinery->maint_freq ?? 'N/A') . '. La información se encuentra en Notificaciones.';
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'from_user_id' => $user->id,
                'machinery_id' => $machinery->id,
                'maintenance_id' => null,
                'type' => 'maintenance_reminder',
                'status' => 'pending',
                'message' => $message,
            ]);
        }
    }
}
