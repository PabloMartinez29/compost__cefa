<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsageControl extends Model
{
    use HasFactory;

    protected $table = 'usage_controls';

    protected $fillable = [
        'machinery_id',
        'date',
        'start_date',
        'end_date',
        'hours',
        'responsible',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Relación con maquinaria
     */
    public function machinery(): BelongsTo
    {
        return $this->belongsTo(Machinery::class);
    }
}



