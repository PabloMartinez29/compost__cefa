<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WarehouseClassification extends Model
{
    use HasFactory;

    protected $table = 'warehouse_classification';

    protected $fillable = [
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
}
