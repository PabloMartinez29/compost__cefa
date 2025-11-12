<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Composting extends Model
{
    protected $fillable = [
        'pile_num',
        'start_date',
        'end_date',
        'total_kg',
        'efficiency',
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
        
        // Si no hay end_date pero se completaron los 45 seguimientos, mostrar como completada
        if ($this->trackings->count() >= 45) {
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
        return now()->diffInDays($this->start_date);
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
        return max(0, 45 - $this->days_elapsed);
    }

    /**
     * Obtener el porcentaje de progreso del proceso basado en seguimientos
     */
    public function getProcessProgressAttribute(): float
    {
        $totalTrackings = $this->trackings->count();
        // El progreso se basa en los seguimientos registrados (máximo 45 seguimientos = 100%)
        return min(($totalTrackings / 45) * 100, 100);
    }

    /**
     * Obtener la fase actual del proceso basada en seguimientos
     */
    public function getCurrentPhaseAttribute(): string
    {
        $totalTrackings = $this->trackings->count();
        
        if ($totalTrackings <= 7) {
            return 'Fase Inicial (Mesófila)';
        } elseif ($totalTrackings <= 21) {
            return 'Fase Termófila (Alta temperatura)';
        } elseif ($totalTrackings <= 35) {
            return 'Fase de Enfriamiento';
        } else {
            return 'Fase de Maduración';
        }
    }

    /**
     * Obtener el progreso de seguimientos (X de 45 seguimientos)
     */
    public function getTrackingProgressAttribute(): string
    {
        $totalTrackings = $this->trackings->count();
        return "{$totalTrackings} de 45 seguimientos";
    }

    /**
     * Verificar si el proceso está completado basado en seguimientos
     */
    public function getIsProcessCompletedByTrackingsAttribute(): bool
    {
        return $this->trackings->count() >= 45;
    }

    /**
     * Accessor para el estado de la pila
     */
    public function getStatusAttribute(): string
    {
        if ($this->end_date) {
            return 'Completada';
        }
        
        // Si no hay end_date pero se completaron los 45 seguimientos, mostrar como completada
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
