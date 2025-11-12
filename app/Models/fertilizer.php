<?php

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
        'notes'
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