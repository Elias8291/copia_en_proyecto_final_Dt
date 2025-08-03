<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActividadProveedor extends Model
{
    protected $table = 'actividades';
    
    protected $fillable = [
        'proveedor_id',
        'tramite_id',
        'actividad_id',
        'status'
    ];

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function tramite(): BelongsTo
    {
        return $this->belongsTo(Tramite::class);
    }

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }
} 