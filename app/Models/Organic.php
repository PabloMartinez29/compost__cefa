<?php

// Modelo Organic — Residuos orgánicos recolectados (tipos, peso, origen)

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organic extends Model
{
    use HasFactory;

    protected $table = 'organics';

    protected $fillable = [
        'date',
        'type',
        'weight',
        'notes',
        'delivered_by',
        'received_by',
        'img',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'weight' => 'decimal:2'
    ];

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    // Accessors
    public function getFormattedWeightAttribute()
    {
        return number_format($this->weight, 2) . ' Kg';
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
            'Other' => 'Otro'
        ];

        return $types[$this->type] ?? $this->type;
    }

    // Relaciones
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessor para información del creador
    public function getCreatedByInfoAttribute()
    {
        if (!$this->creator) {
            return 'Usuario no disponible';
        }

        $role = $this->creator->role === 'admin' ? 'Administrador' : 'Aprendiz';
        return "{$role} - {$this->creator->name}";
    }
}
