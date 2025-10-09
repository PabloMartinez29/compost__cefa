<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ingredient extends Model
{
    protected $fillable = [
        'composting_id',
        'organic_id',
        'amount',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    /**
     * Relación con compostaje
     */
    public function composting(): BelongsTo
    {
        return $this->belongsTo(Composting::class);
    }

    /**
     * Relación con residuo orgánico
     */
    public function organic(): BelongsTo
    {
        return $this->belongsTo(Organic::class);
    }

    /**
     * Accessor para la cantidad formateada
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' Kg';
    }

    /**
     * Accessor para el nombre del ingrediente
     */
    public function getIngredientNameAttribute(): string
    {
        return $this->organic ? $this->organic->type_in_spanish : 'Ingrediente no disponible';
    }
}
