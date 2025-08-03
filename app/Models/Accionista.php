<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Accionista extends Model
{
    protected $table = 'accionistas';
    
    protected $fillable = [
        'nombre',
        'porcentaje_participacion',
        'proveedor_id',
        'tramite_id',
        'rfc',
        'status'
    ];

    protected $casts = [
        'porcentaje_participacion' => 'decimal:2'
    ];

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function tramite(): BelongsTo
    {
        return $this->belongsTo(Tramite::class);
    }
} 