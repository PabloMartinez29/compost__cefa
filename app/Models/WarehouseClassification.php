<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WarehouseClassification extends Model
{
    use HasFactory;

    protected $table = 'warehouse_classification';

    protected $fillable = [
        'organic_id',
        'date',
        'type',
        'movement_type',
        'weight',
        'notes',
        'processed_by',
        'img'
    ];

    protected $casts = [
        'date' => 'date',
        'weight' => 'decimal:2',
    ];

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByMovementType($query, $movementType)
    {
        return $query->where('movement_type', $movementType);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    // Accessors
    public function getFormattedWeightAttribute()
    {
        return number_format($this->weight, 2) . ' kg';
    }

    public function getFormattedDateAttribute()
    {
        return $this->date->format('d/m/Y');
    }

    public function getTypeInSpanishAttribute()
    {
        $types = [
            'Kitchen' => 'Cocina',
            'Beds' => 'Camas',
            'Leaves' => 'Hojas',
            'CowDung' => 'Estiércol de Vaca',
            'ChickenManure' => 'Estiércol de Pollo',
            'PigManure' => 'Estiércol de Cerdo',
            'Other' => 'Otros'
        ];

        return $types[$this->type] ?? $this->type;
    }

    public function getMovementTypeInSpanishAttribute()
    {
        return $this->movement_type === 'entry' ? 'Entrada' : 'Salida';
    }

    /**
     * Relación con el residuo orgánico (solo para entradas automáticas desde residuos).
     */
    public function organic()
    {
        return $this->belongsTo(Organic::class);
    }

    // Método para calcular inventario actual por tipo
    public static function getCurrentInventory($type = null)
    {
        $baseQuery = self::query();
        
        if ($type) {
            $baseQuery->where('type', $type);
        }

        // Crear consultas separadas para evitar conflictos
        $entriesQuery = clone $baseQuery;
        $exitsQuery = clone $baseQuery;
        
        $entries = $entriesQuery->where('movement_type', 'entry')->sum('weight');
        $exits = $exitsQuery->where('movement_type', 'exit')->sum('weight');
        
        return $entries - $exits;
    }

    // Método para obtener inventario por tipo
    public static function getInventoryByType()
    {
        $types = ['Kitchen', 'Beds', 'Leaves', 'CowDung', 'ChickenManure', 'PigManure', 'Other'];
        $inventory = [];

        foreach ($types as $type) {
            $inventory[$type] = self::getCurrentInventory($type);
        }

        return $inventory;
    }

    // Método para validar si hay suficiente inventario disponible
    public static function hasEnoughInventory($type, $weight)
    {
        $currentInventory = self::getCurrentInventory($type);
        return $currentInventory >= $weight;
    }

    // Método para obtener el inventario disponible (no puede ser negativo)
    public static function getAvailableInventory($type)
    {
        $inventory = self::getCurrentInventory($type);
        return max(0, $inventory); // Retorna 0 si es negativo
    }
}
