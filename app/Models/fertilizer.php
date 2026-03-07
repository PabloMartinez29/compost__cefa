<?php

// Modelo Fertilizer — Registro de entregas de abono (sólido/líquido)

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fertilizer extends Model
{
    protected $fillable = [
        'composting_id',
        'date',
        'time',
        'requester',
        'destination',
        'received_by',
        'delivered_by',
        'type',
        'amount',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'string',
        'amount' => 'decimal:2'
    ];

    public function composting(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Composting::class);
    }

    // Relación con el creador
    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('d/m/Y');
    }

    public function getTypeInSpanishAttribute(): string
    {
        return $this->type === 'Liquid' ? 'Líquido' : 'Sólido';
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' ' . ($this->type === 'Liquid' ? 'L' : 'Kg');
    }
}