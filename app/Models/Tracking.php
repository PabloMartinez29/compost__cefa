<?php

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
        'others'
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

    /**
     * Relación con compostaje
     */
    public function composting(): BelongsTo
    {
        return $this->belongsTo(Composting::class);
    }

    /**
     * Accessor para el día formateado
     */
    public function getFormattedDayAttribute(): string
    {
        return 'Día ' . $this->day;
    }

    /**
     * Accessor para la fecha formateada
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('d/m/Y');
    }

    /**
     * Accessor para la temperatura interna formateada
     */
    public function getFormattedTempInternalAttribute(): string
    {
        return $this->temp_internal . '°C';
    }

    /**
     * Accessor para la temperatura ambiente formateada
     */
    public function getFormattedTempEnvAttribute(): string
    {
        return $this->temp_env . '°C';
    }

    /**
     * Accessor para la humedad de la pila formateada
     */
    public function getFormattedHumPileAttribute(): string
    {
        return $this->hum_pile . '%';
    }

    /**
     * Accessor para la humedad ambiente formateada
     */
    public function getFormattedHumEnvAttribute(): string
    {
        return $this->hum_env . '%';
    }

    /**
     * Accessor para el pH formateado
     */
    public function getFormattedPhAttribute(): string
    {
        return $this->ph;
    }

    /**
     * Accessor para el agua formateada
     */
    public function getFormattedWaterAttribute(): string
    {
        return $this->water . 'L';
    }

    /**
     * Accessor para la cal formateada
     */
    public function getFormattedLimeAttribute(): string
    {
        return $this->lime . 'Kg';
    }

    /**
     * Accessor para el tiempo de temperatura formateado
     */
    public function getFormattedTempTimeAttribute(): string
    {
        return $this->temp_time ? $this->temp_time->format('H:i') : 'N/A';
    }

    /**
     * Scope para obtener seguimientos de una pila específica
     */
    public function scopeForComposting($query, $compostingId)
    {
        return $query->where('composting_id', $compostingId);
    }

    /**
     * Scope para obtener seguimientos ordenados por día
     */
    public function scopeOrderedByDay($query)
    {
        return $query->orderBy('day', 'asc');
    }
}
