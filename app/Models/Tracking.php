<?php

// Modelo Tracking — Seguimiento diario de pilas (temperatura, humedad, pH, actividades)

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tracking extends Model
{
    protected $fillable = [
        'composting_id',
        'day',
        'date',
        'activity',
        'work_hours',
        'temp_internal',
        'temp_time',
        'temp_env',
        'hum_pile',
        'hum_env',
        'ph',
        'water',
        'lime',
        'others',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'temp_time' => 'datetime:H:i',
        'temp_internal' => 'decimal:2',
        'temp_env' => 'decimal:2',
        'hum_pile' => 'decimal:2',
        'hum_env' => 'decimal:2',
        'ph' => 'decimal:2',
        'water' => 'decimal:2',
        'lime' => 'decimal:2'
    ];

    // Relación con compostaje
    public function composting(): BelongsTo
    {
        return $this->belongsTo(Composting::class);
    }

    // Día formateado
    public function getFormattedDayAttribute(): string
    {
        return 'Día ' . $this->day;
    }

    // Fecha formateada
    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('d/m/Y');
    }

    // Temperatura interna formateada
    public function getFormattedTempInternalAttribute(): string
    {
        return $this->temp_internal . '°C';
    }

    // Temperatura ambiente formateada
    public function getFormattedTempEnvAttribute(): string
    {
        return $this->temp_env . '°C';
    }

    // Humedad de pila formateada
    public function getFormattedHumPileAttribute(): string
    {
        return $this->hum_pile . '%';
    }

    // Humedad ambiente formateada
    public function getFormattedHumEnvAttribute(): string
    {
        return $this->hum_env . '%';
    }

    // pH formateado
    public function getFormattedPhAttribute(): string
    {
        return $this->ph;
    }

    // Agua formateada
    public function getFormattedWaterAttribute(): string
    {
        return $this->water . 'L';
    }

    // Cal formateada
    public function getFormattedLimeAttribute(): string
    {
        return $this->lime . 'Kg';
    }

    // Hora de temperatura formateada
    public function getFormattedTempTimeAttribute(): string
    {
        return $this->temp_time ? $this->temp_time->format('H:i') : 'N/A';
    }

    // Scope por pila específica
    public function scopeForComposting($query, $compostingId)
    {
        return $query->where('composting_id', $compostingId);
    }

    // Scope ordenado por día
    public function scopeOrderedByDay($query)
    {
        return $query->orderBy('day', 'asc');
    }
}
