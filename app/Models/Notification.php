<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'from_user_id', 
        'organic_id',
        'composting_id',
        'machinery_id',
        'type',
        'status',
        'message',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime'
    ];

    // Relaciones
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function organic(): BelongsTo
    {
        return $this->belongsTo(Organic::class);
    }

    public function composting(): BelongsTo
    {
        return $this->belongsTo(Composting::class);
    }

    public function machinery(): BelongsTo
    {
        return $this->belongsTo(Machinery::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    // Accessors
    public function getTypeNameAttribute()
    {
        return match($this->type) {
            'delete_request' => 'Solicitud de Eliminación',
            'edit_request' => 'Solicitud de Edición',
            'maintenance_reminder' => 'Recordatorio de Mantenimiento',
            default => 'Desconocido'
        };
    }

    public function getStatusNameAttribute()
    {
        return match($this->status) {
            'pending' => 'Pendiente',
            'approved' => 'Aprobada',
            'rejected' => 'Rechazada',
            'processed' => 'Procesada',
            default => 'Desconocido'
        };
    }
}
