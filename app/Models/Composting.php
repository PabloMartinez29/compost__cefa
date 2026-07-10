<?php

// Modelo Composting — Gestión de pilas de compostaje (fases, progreso, estado)

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

    protected $appends = [
        'status',
        'days_elapsed'
    ];

    // Relación con ingredientes
    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class);
    }

    // Relación con seguimientos
    public function trackings(): HasMany
    {
        return $this->hasMany(Tracking::class);
    }

    // Relación con fertilizantes
    public function fertilizers(): HasMany
    {
        return $this->hasMany(\App\Models\Fertilizer::class);
    }

    // Número de pila formateado
    public function getFormattedPileNumAttribute(): string
    {
        return 'P-' . str_pad($this->pile_num, 3, '0', STR_PAD_LEFT);
    }

    // Fecha de inicio formateada
    public function getFormattedStartDateAttribute(): string
    {
        return $this->start_date->format('d/m/Y');
    }

    // Fecha de fin formateada
    public function getFormattedEndDateAttribute(): string
    {

        if ($this->end_date) {
            return $this->end_date->format('d/m/Y');
        }

        if ($this->status === 'Completada') {
            if ($this->start_date) {
                return $this->start_date->copy()->addDays(44)->format('d/m/Y');
            }

            return 'Completada';
        }


        return 'En proceso';
    }

    // Peso total formateado
    public function getFormattedTotalKgAttribute(): string
    {
        if ($this->total_kg === null) {
            return $this->end_date ? 'No registrado' : 'En proceso';
        }
        return number_format($this->total_kg, 2) . ' Kg';
    }

    // Eficiencia formateada
    public function getFormattedEfficiencyAttribute(): string
    {
        return $this->efficiency ? number_format($this->efficiency, 2) . '%' : 'No calculada';
    }

    // Total de ingredientes
    public function getTotalIngredientsAttribute(): int
    {
        return $this->ingredients()->count();
    }

    // Total de ingredientes formateado
    public function getFormattedTotalIngredientsAttribute(): string
    {
        $total = $this->total_ingredients;
        return $total . ' ingrediente' . ($total !== 1 ? 's' : '');
    }

    // Relación con el creador
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Días transcurridos desde el inicio
    public function getDaysElapsedAttribute(): int
    {
        $startDate = Carbon::parse($this->start_date);
        $now = Carbon::now();
        
        if ($now->lt($startDate)) {
            return 0;
        }
        
        $days = $startDate->diffInDays($now);
        
        return max(0, $days);
    }

    // Verificar si completó 45 días
    public function getIsProcessCompletedAttribute(): bool
    {
        return $this->days_elapsed >= 45;
    }

    // Días restantes del proceso
    public function getDaysRemainingAttribute(): int
    {

        if ($this->status === 'Completada' || $this->days_elapsed >= 45) {
            return 0;
        }
        return max(0, 45 - $this->days_elapsed);
    }

    // Porcentaje de progreso del proceso
    public function getProcessProgressAttribute(): float
    {
        if ($this->status === 'Completada' || $this->days_elapsed >= 45) {
            return 100;
        }
        

        $maxTrackingDay = 0;
        if ($this->trackings->count() > 0) {
            $maxTrackingDay = $this->trackings->max('day');
        }
        
        $daysElapsed = min(max(max(0, $this->days_elapsed), $maxTrackingDay), 45);
        return min(($daysElapsed / 45) * 100, 100);
    }

    // Fase actual del proceso
    public function getCurrentPhaseAttribute(): string
    {
        if ($this->status === 'Completada' || $this->days_elapsed >= 45) {
            return 'Proceso Completado';
        }
        
        $daysElapsed = max(0, $this->days_elapsed);
        
        if ($daysElapsed <= 7) {
            return 'Fase Inicial (Mesófila)';
        } elseif ($daysElapsed <= 21) {
            return 'Fase Termófila (Alta temperatura)';
        } elseif ($daysElapsed <= 35) {
            return 'Fase de Enfriamiento';
        } else {
            return 'Fase de Maduración';
        }
    }

    // Color de la barra de progreso
    public function getProgressBarColorAttribute(): string
    {
        if ($this->status === 'Completada' || $this->days_elapsed >= 45) {
            return 'bg-green-600';
        }
        
        $daysElapsed = max(0, $this->days_elapsed);
        
        if ($daysElapsed <= 7) {
            return 'bg-green-400';
        } elseif ($daysElapsed <= 21) {
            return 'bg-orange-500';
        } elseif ($daysElapsed <= 35) {
            return 'bg-blue-500';
        } else {
            return 'bg-green-700';
        }
    }

    // Progreso de seguimientos
    public function getTrackingProgressAttribute(): string
    {
        if ($this->status === 'Completada' || $this->days_elapsed >= 45) {
            $totalTrackings = $this->trackings->count();
            return "45 de 45 días ({$totalTrackings} seguimientos registrados)";
        }
        

        $maxTrackingDay = 0;
        if ($this->trackings->count() > 0) {
            $maxTrackingDay = $this->trackings->max('day');
        }
        
        $daysElapsed = min(max(max(0, $this->days_elapsed), $maxTrackingDay), 45);
        $totalTrackings = $this->trackings->count();
        return "{$daysElapsed} de 45 días ({$totalTrackings} seguimientos registrados)";
    }

    // Completado por seguimientos
    public function getIsProcessCompletedByTrackingsAttribute(): bool
    {
        return $this->trackings->count() >= 45;
    }

    // Días sin seguimiento registrado
    public function getMissingDaysAttribute(): array
    {
        $missingDays = [];
        
        // Obtener el día máximo registrado en los seguimientos
        $maxTrackingDay = 0;
        if ($this->trackings->count() > 0) {
            $maxTrackingDay = $this->trackings->max('day');
        }
        

        $daysElapsed = ($this->status === 'Completada' || $this->days_elapsed >= 45) ? 45 : min(max($this->days_elapsed, $maxTrackingDay), 45);
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

    // Estado de la pila
    public function getStatusAttribute(): string
    {
        if ($this->end_date) {
            return 'Completada';
        }
        

        if ($this->days_elapsed >= 45) {
            return 'Completada';
        }
        

        if ($this->trackings->count() >= 45) {
            return 'Completada';
        }
        
        return 'En Proceso';
    }

    // Estado formateado con colores
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
