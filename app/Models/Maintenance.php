<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Maintenance extends Model
{
    use HasFactory;

    protected $table = 'maintenances';

    protected $fillable = [
        'machinery_id',
        'date',
        'end_date',
        'type',
        'description',
        'responsible',
    ];

    protected $casts = [
        'date' => 'date',
        'end_date' => 'date',
    ];

    // Relación con maquinaria
    public function machinery()
    {
        return $this->belongsTo(Machinery::class);
    }

    // Accessor para el tipo de mantenimiento
    public function getTypeNameAttribute()
    {
        return match($this->type) {
            'O' => 'Operación',
            'M' => 'Mantenimiento',
            default => 'Desconocido'
        };
    }

    // Scope para filtrar por tipo
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope para mantenimientos
    public function scopeMaintenance($query)
    {
        return $query->where('type', 'M');
    }

    // Scope para operaciones
    public function scopeOperations($query)
    {
        return $query->where('type', 'O');
    }

    // Scope para una maquinaria específica
    public function scopeForMachinery($query, $machineryId)
    {
        return $query->where('machinery_id', $machineryId);
    }
}





