<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'machinery_id',
        'maker',
        'origin',
        'purchase_date',
        'supplier',
        'phone',
        'email',
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    /**
     * Relación con maquinaria
     */
    public function machinery(): BelongsTo
    {
        return $this->belongsTo(Machinery::class);
    }
}



