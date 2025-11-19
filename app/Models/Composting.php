<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Composting extends Model
{
    protected $fillable = [
        'pile_num',
        'start_date',
        'end_date',
        'total_kg',
        'efficiency',
        'image',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_kg' => 'decimal:2',
        'efficiency' => 'decimal:2'
    ];

    /**
     * Relación con ingredientes
     */
    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class);
    }

    /**
     * Relación con seguimientos
     */
    public function trackings(): HasMany
    {
        return $this->hasMany(Tracking::class);
    }

    /**
     * Relación con fertilizantes (abono)
     */
    public function fertilizers(): HasMany
    {
        return $this->hasMany(\App\Models\Fertilizer::class);
    }

    /**
     * Accessor para el número de pila formateado
     */
    public function getFormattedPileNumAttribute(): string
    {
        return 'P-' . str_pad($this->pile_num, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Accessor para la fecha de inicio formateada
     */
    public function getFormattedStartDateAttribute(): string
    {
        return $this->start_date->format('d/m/Y');
    }

    /**
     * Accessor para la fecha de fin formateada
     */
    public function getFormattedEndDateAttribute(): string
    {
        if ($this->end_date) {
            return $this->end_date->format('d/m/Y');
        }
        
        // Si han pasado 45 días desde el inicio, mostrar como completada
        if ($this->days_elapsed >= 45) {
            return 'Completada';
        }
        
        return 'En proceso';
    }

    /**
     * Accessor para el peso total formateado
     */
    public function getFormattedTotalKgAttribute(): string
    {
        if ($this->total_kg === null) {
            return $this->end_date ? 'No registrado' : 'En proceso';
        }
        return number_format($this->total_kg, 2) . ' Kg';
    }

    /**
     * Accessor para la eficiencia formateada
     */
    public function getFormattedEfficiencyAttribute(): string
    {
        return $this->efficiency ? number_format($this->efficiency, 2) . '%' : 'No calculada';
    }

    /**
     * Accessor para el total de ingredientes
     */
    public function getTotalIngredientsAttribute(): int
    {
        return $this->ingredients()->count();
    }

    /**
     * Accessor para el total de ingredientes formateado
     */
    public function getFormattedTotalIngredientsAttribute(): string
    {
        $total = $this->total_ingredients;
        return $total . ' ingrediente' . ($total !== 1 ? 's' : '');
    }

    /**
     * Relación con el usuario que creó la pila
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Calcular días transcurridos desde el inicio
     */
    public function getDaysElapsedAttribute(): int
    {
        $startDate = Carbon::parse($this->start_date);
        $now = Carbon::now();
        
        // Si la fecha de inicio es en el futuro, devolver 0
        if ($now->lt($startDate)) {
            return 0;
        }
        
        // Calcular días transcurridos desde la fecha de inicio
        $days = $startDate->diffInDays($now);
        
        // Asegurar que siempre sea un valor positivo
        return max(0, $days);
    }

    /**
     * Verificar si el proceso está completado (45 días)
     */
    public function getIsProcessCompletedAttribute(): bool
    {
        return $this->days_elapsed >= 45;
    }

    /**
     * Obtener días restantes del proceso
     */
    public function getDaysRemainingAttribute(): int
    {
        // Si el estado es completada o han pasado 45 días, no hay días restantes
        if ($this->status === 'Completada' || $this->days_elapsed >= 45) {
            return 0;
        }
        return max(0, 45 - $this->days_elapsed);
    }

    /**
     * Obtener el porcentaje de progreso del proceso basado en días transcurridos
     */
    public function getProcessProgressAttribute(): float
    {
        $daysElapsed = max(0, $this->days_elapsed);
        // El progreso se basa en los días transcurridos (máximo 45 días = 100%)
        return min(($daysElapsed / 45) * 100, 100);
    }

    /**
     * Obtener la fase actual del proceso basada en días transcurridos
     */
    public function getCurrentPhaseAttribute(): string
    {
        $daysElapsed = max(0, $this->days_elapsed);
        
        if ($daysElapsed >= 45) {
            return 'Proceso Completado';
        } elseif ($daysElapsed <= 7) {
            return 'Fase Inicial (Mesófila)';
        } elseif ($daysElapsed <= 21) {
            return 'Fase Termófila (Alta temperatura)';
        } elseif ($daysElapsed <= 35) {
            return 'Fase de Enfriamiento';
        } else {
            return 'Fase de Maduración';
        }
    }

    /**
     * Obtener el color de la barra de progreso según la fase
     */
    public function getProgressBarColorAttribute(): string
    {
        $daysElapsed = max(0, $this->days_elapsed);
        
        if ($daysElapsed >= 45) {
            return 'bg-green-600'; // Verde completo para proceso completado
        } elseif ($daysElapsed <= 7) {
            return 'bg-green-400'; // Verde claro para fase inicial
        } elseif ($daysElapsed <= 21) {
            return 'bg-orange-500'; // Naranja para fase termófila (alta temperatura)
        } elseif ($daysElapsed <= 35) {
            return 'bg-blue-500'; // Azul para fase de enfriamiento
        } else {
            return 'bg-green-700'; // Verde oscuro para fase de maduración
        }
    }

    /**
     * Obtener el progreso de seguimientos (X de 45 días)
     */
    public function getTrackingProgressAttribute(): string
    {
        $daysElapsed = min(max(0, $this->days_elapsed), 45);
        $totalTrackings = $this->trackings->count();
        return "{$daysElapsed} de 45 días ({$totalTrackings} seguimientos registrados)";
    }

    /**
     * Verificar si el proceso está completado basado en seguimientos
     */
    public function getIsProcessCompletedByTrackingsAttribute(): bool
    {
        return $this->trackings->count() >= 45;
    }

    /**
     * Obtener los días faltantes (días sin seguimiento registrado)
     */
    public function getMissingDaysAttribute(): array
    {
        $missingDays = [];
        
        // Si la pila está completada o han pasado 45 días, mostrar los 45 días completos
        $daysElapsed = ($this->status === 'Completada' || $this->days_elapsed >= 45) ? 45 : min($this->days_elapsed, 45);
        $trackedDays = $this->trackings->pluck('day')->toArray();
        
        $startDate = Carbon::parse($this->start_date);
        
        for ($day = 1; $day <= $daysElapsed; $day++) {
            if (!in_array($day, $trackedDays)) {
                $date = $startDate->copy()->addDays($day - 1);
                $missingDays[] = [
                    'day' => $day,
                    'date' => $date->format('d/m/Y'),
                    'date_raw' => $date->format('Y-m-d')
                ];
            }
        }
        
        return $missingDays;
    }

    /**
     * Accessor para el estado de la pila
     */
    public function getStatusAttribute(): string
    {
        if ($this->end_date) {
            return 'Completada';
        }
        
        // Si han pasado 45 días desde el inicio, marcar como completada
        if ($this->days_elapsed >= 45) {
            return 'Completada';
        }
        
        // Si hay 45 seguimientos registrados, marcar como completada
        if ($this->trackings->count() >= 45) {
            return 'Completada';
        }
        
        return 'En Proceso';
    }

    /**
     * Accessor para el estado formateado con colores
     */
    public function getFormattedStatusAttribute(): string
    {
        $status = $this->status;
        
        if ($status === 'Completada') {
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>
                        Completada
                    </span>';
        }
        
        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    <i class="fas fa-clock mr-1"></i>
                    En Proceso
                </span>';
    }
}
